<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // Create 2 Admin Users
        $admin1 = User::create([
            'name' => 'Talukder Agro Eco Farm',
            'email' => 'a@b.c',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'created_by' => null,
        ]);

        $admin2 = User::create([
            'name' => 'Second Admin',
            'email' => 'admin2@talukderagro.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'created_by' => $admin1->id,
        ]);

        // Create 2 Staff Users
        User::create([
            'name' => 'Staff One',
            'email' => 'staff1@talukderagro.com',
            'password' => Hash::make('password'),
            'role' => 'staff',
            'created_by' => $admin1->id,
        ]);

        User::create([
            'name' => 'Staff Two',
            'email' => 'staff2@talukderagro.com',
            'password' => Hash::make('password'),
            'role' => 'staff',
            'created_by' => $admin1->id,
        ]);

        // Create 2 Customer Users
        User::create([
            'name' => 'Customer One',
            'email' => 'customer1@talukderagro.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'created_by' => $admin1->id,
        ]);

        User::create([
            'name' => 'Customer Two',
            'email' => 'customer2@talukderagro.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'created_by' => $admin1->id,
        ]);
    }
}
