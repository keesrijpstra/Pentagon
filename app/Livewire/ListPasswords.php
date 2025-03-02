<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Password;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;

class ListPasswords extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    
    public function table(Table $table): Table
    {
        return $table
            ->query(Password::query()->where('user_id', '=', auth()->user()->id))
            ->columns([
                TextColumn::make('title'),
                TextColumn::make('username'),
                TextColumn::make('password'),
                TextColumn::make('url'),
                TextColumn::make('created_at')
            ])
            ->emptyStateHeading('No passwords yet');
    }

    public function render()
    {
        return view('livewire.list-passwords');
    }
}