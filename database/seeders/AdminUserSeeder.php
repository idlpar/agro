<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // Create Admin Users
        $admin1 = User::create([
            'name' => 'Talukder Agro Eco Farm',
            'email' => 'a@b.c',
            'phone' => '01711223344',
            'address' => 'Farm Address, Dhaka',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'created_by' => null,
        ]);

        $admin2 = User::create([
            'name' => 'Second Admin',
            'email' => 'admin2@talukderagro.com',
            'phone' => '01711223345',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'created_by' => $admin1->id,
        ]);

        // Create 5 Staff Users
        $staffs = [
            [
                'name' => 'Abdul Karim',
                'email' => 'karim@talukderagro.com',
                'phone' => '01711223346',
                'password' => Hash::make('password'),
                'role' => 'staff',
                'created_by' => $admin1->id,
            ],
            [
                'name' => 'Rahim Uddin',
                'email' => 'rahim@talukderagro.com',
                'phone' => '01711223347',
                'password' => Hash::make('password'),
                'role' => 'staff',
                'created_by' => $admin1->id,
            ],
            [
                'name' => 'Fatema Begum',
                'email' => 'fatema@talukderagro.com',
                'phone' => '01711223348',
                'password' => Hash::make('password'),
                'role' => 'staff',
                'created_by' => $admin1->id,
            ],
            [
                'name' => 'Jamal Hossain',
                'email' => 'jamal@talukderagro.com',
                'phone' => '01711223349',
                'password' => Hash::make('password'),
                'role' => 'staff',
                'created_by' => $admin1->id,
            ],
            [
                'name' => 'Nusrat Jahan',
                'email' => 'nusrat@talukderagro.com',
                'phone' => '01711223350',
                'password' => Hash::make('password'),
                'role' => 'staff',
                'created_by' => $admin1->id,
            ]
        ];

        foreach ($staffs as $staff) {
            User::create($staff);
        }

        // Create 5 Customer Users
        $customers = [
            [
                'name' => 'Mohammad Ali',
                'email' => 'ali@customer.com',
                'phone' => '01711224455',
                'address' => 'House 10, Road 5, Dhaka',
                'password' => Hash::make('password'),
                'role' => 'customer',
                'created_by' => $admin1->id,
            ],
            [
                'name' => 'Rahima Akter',
                'email' => 'rahima@customer.com',
                'phone' => '01711224456',
                'address' => 'House 20, Road 6, Dhaka',
                'password' => Hash::make('password'),
                'role' => 'customer',
                'created_by' => $admin1->id,
            ],
            [
                'name' => 'Kamal Hossain',
                'email' => 'kamal@customer.com',
                'phone' => '01711224457',
                'address' => 'House 30, Road 7, Dhaka',
                'password' => Hash::make('password'),
                'role' => 'customer',
                'created_by' => $admin1->id,
            ],
            [
                'name' => 'Shirin Sultana',
                'email' => 'shirin@customer.com',
                'phone' => '01711224458',
                'address' => 'House 40, Road 8, Dhaka',
                'password' => Hash::make('password'),
                'role' => 'customer',
                'created_by' => $admin1->id,
            ],
            [
                'name' => 'Abdur Razzak',
                'email' => 'razzak@customer.com',
                'phone' => '01711224459',
                'address' => 'House 50, Road 9, Dhaka',
                'password' => Hash::make('password'),
                'role' => 'customer',
                'created_by' => $admin1->id,
            ]
        ];

        foreach ($customers as $customer) {
            User::create($customer);
        }
    }
}
