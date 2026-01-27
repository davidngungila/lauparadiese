<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;

class VerifyUsers extends Command
{
    protected $signature = 'users:verify';
    protected $description = 'Verify all seeded users and their roles';

    public function handle()
    {
        $this->info('=== User Verification Report ===');
        $this->newLine();

        $totalUsers = User::count();
        $this->info("Total Users: {$totalUsers}");
        $this->newLine();

        $this->info('Users by Role:');
        $this->newLine();

        $roles = Role::orderBy('name')->get();
        
        foreach ($roles as $role) {
            $users = $role->users;
            $count = $users->count();
            
            if ($count > 0) {
                $this->line("  {$role->name}: {$count} user(s)");
                foreach ($users as $user) {
                    $this->line("    - {$user->name} ({$user->email})");
                }
                $this->newLine();
            }
        }

        $this->info('✅ Verification complete!');
        
        return 0;
    }
}









