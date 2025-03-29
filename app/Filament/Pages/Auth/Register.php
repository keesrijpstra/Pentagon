<?php

namespace App\Filament\Pages\Auth;

use App\Models\User;
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
        return Select::make('roleSelection') // Use a field name that doesn't conflict
            ->options([
                'user' => 'User'
            ])
            ->default('user')
            ->required();
    }

    protected function getFormModel(): string
    {
        return User::class;
    }

    protected function handleRegistration(array $data): User
    {
        // Remove the role selection from data
        $selectedRole = $data['roleSelection'] ?? 'user';
        unset($data['roleSelection']);
        
        // Create user without the role field
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            // No role field here
        ]);
        
        // Assign role using Spatie's method
        $user->assignRole($selectedRole);
        
        return $user;
    }
}