<?php

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Component;
use Filament\Pages\Auth\Register as BaseRegister;
 
class Register extends BaseRegister 
{
    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
                        $this->getNameFormComponent(),
                        $this->getEmailFormComponent(),
                        $this->getPasswordFormComponent(),
                        $this->getPasswordConfirmationFormComponent(),
                        $this->getRoleFormComponent(), 
                    ])
                    ->statePath('data'),
            ),
        ];
    }

    protected function getRoleFormComponent(): Component
    {
        return Select::make('getRoles')
            ->options([
                'user' => 'user'
            ])
            ->default('user')
            ->required();
    }
}
