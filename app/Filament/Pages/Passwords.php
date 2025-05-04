<?php

namespace App\Filament\Pages;

use App\Models\User;
use App\Models\Password;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Forms\Components\Group;
use Filament\Actions\Concerns\HasForm;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Pages\Concerns\InteractsWithFormActions;

class Passwords extends Page
{
    use HasPageShield;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.passwords';

    protected $user = null;

    public $formTitle = '';
    public $username = '';  
    public $password = '';
    public $url = '';

    public static function canAccess(): bool
    {
        return auth()->user()->can('page_passwords_view');
    }

    protected function canCreate(): bool
    {
        return auth()->user()->can('page_custom-page_create');
    }
    
    protected function canUpdate(): bool
    {
        return auth()->user()->can('page_custom-page_update');
    }
    
    protected function canDelete(): bool
    {
        return auth()->user()->can('page_custom-page_delete');
    }

    public function mount()
    {
        $currentUser = auth()->user();

        if (!$currentUser->id)
        {
            return;
        }
    }

    public static function getNavigationBadge(): ?string
    {
        $count = Password::query()->where('user_id', auth()->user()->id)->count();
        return $count > 0 ? (string) $count : null;
    }

    protected function getActions(): array
    {
        return [
            Action::make('create')
                ->label('New password')
                ->slideOver()
                ->visible(fn (): bool => $this->canCreate())
                ->form([
                    Group::make()
                        ->schema([
                                TextInput::make('formTitle')
                                    ->label('Title')
                                    ->required(),
                                TextInput::make('username')
                                    ->required(),
                            ])->columns(2),
                    Group::make()
                        ->schema([
                            TextInput::make('password')
                                ->password()
                                ->required(),
                            TextInput::make('url')
                        ])->columns(2)
                ])
                ->action(function (array $data): void {
                    Password::create([
                        'user_id' => auth()->id(),
                        'title' => $data['formTitle'],
                        'username' => $data['username'],
                        'password' => $data['password'], 
                        'url' => $data['url'],
                    ]);

                    $user = auth()->user();
                    
                    Notification::make()
                        ->title('Password for ' . '<strong>' . $data['formTitle'] . '</strong>' . ' stored successfully')
                        ->success()
                        ->sendToDatabase($user)
                        ->send();
                }),
        ];
    }

    public function createPassword(): void
    {
        $this->dispatch('open-modal', id: 'create-password-modal');
    }

    public function table()
    {
        return view('livewire.list-passwords');
    }
}
