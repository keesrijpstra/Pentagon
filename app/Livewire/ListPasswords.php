<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Password;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Group;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\TextInput;
use Filament\Support\Enums\IconPosition;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Forms\Components\Section as FormSection;
use Filament\Infolists\Components\Group as TableGroup;
use Filament\Forms\Components\Actions\Action as FormAction;
use Filament\Forms\Components\Actions\Action as FormActionTable;

class ListPasswords extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->query(Password::query()->where('user_id', '=', auth()->user()->id))
            ->columns([
                TextColumn::make('url')
                    ->label('Website')
                    ->searchable()
                    ->url(fn (Password $record): string => $record->url)
                    ->openUrlInNewTab()
                    ->icon('heroicon-c-arrow-up-right')
                    ->iconPosition(IconPosition::After)
                    ->limit(10)
                    ->tooltip(fn (Password $record): string => $record->url)
                    ->iconColor('primary'),
                TextColumn::make('username')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->icon('heroicon-o-user'),
                TextColumn::make('password')
                    ->label('Password')
                    ->copyable()
                    ->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('is_favorite')
                    ->label('Favorite')
                    ->boolean()
                    ->sortable()
                    ->trueIcon('heroicon-s-star')
                    ->falseIcon('heroicon-o-star')
                    ->trueColor('warning')
                    ->falseColor('gray')
                    ->action(function (Password $record, $state) {
                        $record->is_favorite = !$state;
                        $record->save();
                    })
                    ->tooltip(fn (Password $record): string => $record->is_favorite ? 'Remove from favorites' : 'Add to favorites'),

                TextColumn::make('created_at')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),

                TextColumn::make('updated_at')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                BadgeColumn::make('strength')
                    ->colors([
                        'danger' => 'weak',
                        'warning' => 'medium',
                        'success' => 'strong',
                    ])
                    ->toggleable(),
            ])
            ->recordAction('edit')
            ->defaultSort('created_at', 'desc')
            ->filters([
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from'),
                        DatePicker::make('created_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                ActionGroup::make([
                    ViewAction::make()
                        ->icon('heroicon-o-eye')
                        ->infolist([
                            TableGroup::make([
                                TextEntry::make('url')
                                    ->url(fn (Password $record): string => $record->url)
                                    ->openUrlInNewTab()
                                    ->icon('heroicon-c-arrow-up-right')
                                    ->iconPosition(IconPosition::After)
                                    ->iconColor('primary'),
                                TextEntry::make('email'),
                                TextEntry::make('username'),
                            ])->columns(2),
                            TableGroup::make([
                                TextEntry::make('password')
                                    ->label('Password')
                                    ->getStateUsing(fn ($record) => $record->password)
                                    ->formatStateUsing(fn ($state): string => Str::mask($state, '*', 0))
                                    ->copyable()
                                    ->copyableState(fn (Password $record): string => $record->password)
                                    ->copyMessage('Password copied!')
                                    ->icon('heroicon-o-key')
                                    ->iconPosition(IconPosition::After)
                                    ->iconColor('primary')
                                    ->limit(20),
                            ])->columns(2),
                        ])
                        ->slideOver()
                        ->modalWidth('2xl'),

                    Action::make('copy_password')
                        ->icon('heroicon-o-clipboard')
                        ->label('Copy Password')
                        ->color('gray')
                        ->action(function (Password $record) {
                            $this->dispatch('password-copied', password: $record->password);
                        }),

                    EditAction::make('edit')
                        ->icon('heroicon-o-pencil')
                        ->slideOver()
                        ->form([
                            FormSection::make('Website Details')
                                ->compact()
                                ->description('Update the basic information for this password.')
                                ->schema([
                                    TextInput::make('title')
                                        ->label('Title')
                                        ->required()
                                        ->maxLength(255),
                                    TextInput::make('url')
                                        ->label('Website')
                                        ->url()
                                        ->required()
                                        ->suffixAction(
                                            FormAction::make('visit')
                                                ->icon('heroicon-m-arrow-top-right-on-square')
                                                ->tooltip('Visit website')
                                                ->url(fn (TextInput $component) => $component->getState(), true)
                                        )
                                        ->maxLength(255),
                                ]),
                            
                            FormSection::make('Credentials')
                                ->compact()
                                ->description('Update the login credentials for this website.')
                                ->schema([
                                    Group::make([
                                        TextInput::make('email')
                                            ->label('Email')
                                            ->email()
                                            ->maxLength(255),
                                        TextInput::make('username')
                                            ->label('Username')
                                            ->required()
                                            ->maxLength(255),

                                    ])->columns(2),
                                    TextInput::make('password')
                                    ->label('Password')
                                    ->password()
                                    ->revealable()
                                    ->dehydrateStateUsing(fn ($state) => filled($state) ? Hash::make($state) : null)
                                    ->dehydrated(fn ($state) => filled($state))
                                    ->helperText('Leave blank to keep current password')
                                    ->suffixActions([
                                        FormActionTable::make('copy')
                                            ->icon('heroicon-s-clipboard')
                                            ->action(function ($livewire, TextInput $component) {
                                                $livewire->dispatch('notify', type: 'success', message: 'Password copied!');
                                                $livewire->dispatch('copy-password', text: $component->getState());
                                            }),
                                        FormActionTable::make('generate')
                                            ->icon('heroicon-m-sparkles')
                                            ->action(function (TextInput $component) {
                                                $component->state(Str::password(16, symbols: true));
                                            })
                                    ]),
                            ]),
                            
                            FormSection::make('Additional Settings')
                                ->collapsed()
                                ->schema([
                                    Toggle::make('is_favorite')
                                        ->label('Mark as favorite')
                                        ->helperText('Add this to your favorites list'),
                                ]),
                        ])
                        ->mutateFormDataUsing(function (array $data): array {
                            if (empty($data['password'])) {
                                unset($data['password']);
                            }
                            
                            return $data;
                        }),

                    DeleteAction::make()
                        ->icon('heroicon-o-trash'),
                ]),
            ])
            ->bulkActions([
            ])
            ->emptyStateHeading('No passwords yet')
            ->emptyStateDescription('Create your first password entry to get started with secure password management.')
            ->emptyStateIcon('heroicon-o-lock-closed')
            ->emptyStateActions([
                Action::make('create')
                    ->label('Create Password')
                    ->icon('heroicon-o-plus')
                    ->button(),
            ])
            ->paginated([10, 25, 50, 100])
            ->poll('60s');
    }

    #[On('copy-password')]
    public function copyPassword($text)
    {
        $this->dispatch('clipboard-copy', text: $text);
    }

    

    public function render()
    {
        return view('livewire.list-passwords');
    }
}


