<?php

namespace Database\Seeders;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('123456'),
                'role' => 'admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'customer@gmail.com'],
            [
                'name' => 'Ritik Customer',
                'password' => Hash::make('123456'),
                'role' => 'customer',
            ]
        );

        User::updateOrCreate(
            ['email' => 'customer2@gmail.com'],
            [
                'name' => 'Ram Customer',
                'password' => Hash::make('123456'),
                'role' => 'customer',
            ]
        );

        User::updateOrCreate(
            ['email' => 'vendor1@gmail.com'],
            [
                'name' => 'Ritik Vendor',
                'password' => Hash::make('123456'),
                'role' => 'vendor',
            ]
        );

        User::updateOrCreate(
            ['email' => 'vendor2@gmail.com'],
            [
                'name' => 'Piyush Vendor',
                'password' => Hash::make('123456'),
                'role' => 'vendor',
            ]
        );
    }
}
