<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@balanacanport.gov.ph'],
            [
                'name'     => 'Port Administrator',
                'password' => Hash::make('Admin@1234!'),
                'is_admin' => true,
            ]
        );

        $this->command->info('Admin user created: admin@balanacanport.gov.ph / Admin@1234!');
        $this->command->warn('⚠️  Change the password immediately after first login!');
    }
}