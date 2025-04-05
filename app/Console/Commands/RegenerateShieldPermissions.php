<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Providers\ShieldPagePermissionsProvider;
use Illuminate\Support\Facades\Artisan;

class RegenerateShieldPermissions extends Command
{
    protected $signature = 'shield:regenerate-permissions';
    
    protected $description = 'Regenerate all Shield permissions including enhanced page permissions';
    
    public function handle(): int
    {
        $this->info('Regenerating Shield permissions...');
        
        // Check available options for shield:generate
        $this->info('Running standard shield:generate command...');
        
        // Run shield:generate without any options
        Artisan::call('shield:generate');
        $this->info(Artisan::output());
        
        $this->info('Standard permissions generated');
        
        // Create provider instance
        $provider = new ShieldPagePermissionsProvider(app());
        
        // Use reflection to access the protected method
        $method = new \ReflectionMethod($provider, 'generatePagePermissions');
        $method->setAccessible(true);
        $method->invoke($provider);
        
        $this->info('Enhanced page permissions generated');
        
        $this->info('All permissions have been regenerated successfully!');
        
        return Command::SUCCESS;
    }
}