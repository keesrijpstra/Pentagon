<?php

namespace App\Filament\Pages;

use App\Models\Password;
use App\Models\User;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Forms\Components\Group;
use Filament\Actions\Concerns\HasForm;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;

class Passwords extends Page 
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.passwords';

    protected $user = null;

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
        return Password::count();
    }

    protected function getActions(): array
    {
        return [
            Action::make('create')
                ->label('New')
                ->slideOver()
                ->form([
                    Group::make()
                        ->schema([
                                TextInput::make('title')
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
                ]),
        ];
    }
    
    public function openSettingsModal(): void
    {
        $this->dispatchBrowserEvent('open-settings-modal');
    }

    public function table()
    {
        return view('livewire.list-passwords');
    }
}
