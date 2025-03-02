<?php

namespace App\Filament\Pages;

use App\Models\User;
use Filament\Actions\Concerns\HasForm;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Pages\Page;

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
}
