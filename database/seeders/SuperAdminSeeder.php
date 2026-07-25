<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'superadmin@sarrafpro.com'],
            [
                'name'     => 'Super Admin',
                'email'    => 'superadmin@sarrafpro.com',
                'password' => Hash::make('Admin@12345'),
                'role'     => 'super_admin',
            ]
        );
    }
}
