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
            ['email' => 'admin@sarrafhpro.com'],
            [
                'name'     => 'Super Admin',
                'email'    => 'admin@sarrafhpro.com',
                'password' => Hash::make('Admin@123456'),
                'role'     => 'super_admin',
                'is_active'=> true,
            ]
        );
    }
}
