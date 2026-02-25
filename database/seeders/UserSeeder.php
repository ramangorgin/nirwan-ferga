<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Your specific admin user
        User::create([
            'name' => 'رامان گرگین پاوه',
            'email' => 'raman.gorginpaveh@gmail.com',
            'password' => Hash::make('2751'),
            'role' => 'admin',
            'phone' => '09014282751',
            'avatar' => 'avatars/1/avatar.jpg', // Place your avatar file at: storage/app/public/avatars/1/avatar.jpg
            'gender' => 'male',
            'birthdate' => '2004-06-03',
            'country' => 'iran',
            'city' => 'karaj',
            'timezone' => 'Asia/Tehran',
            'status' => 'active',
            'email_verified_at' => now(),
            'phone_verified_at' => now(),
        ]);

        // Additional users with various statuses
        User::create([
            'name' => 'دلنیا محمدی',
            'email' => 'delnya.mohammadi@example.com',
            'password' => Hash::make('password123'),
            'role' => 'teacher',
            'phone' => '09121234567',
            'gender' => 'female',
            'birthdate' => '1990-05-15',
            'country' => 'ایران',
            'city' => 'تهران',
            'timezone' => 'Asia/Tehran',
            'status' => 'active',
            'email_verified_at' => now(),
            'phone_verified_at' => now(),
        ]);

        User::create([
            'name' => 'ئاکام رەسوڵ',
            'email' => 'akam.rasul@example.com',
            'password' => Hash::make('password123'),
            'role' => 'student',
            'phone' => '09187654321',
            'gender' => 'male',
            'birthdate' => '2002-08-20',
            'country' => 'عێراق',
            'city' => 'سلێمانی',
            'timezone' => 'Asia/Baghdad',
            'status' => 'pending',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'سارا احمدی',
            'email' => 'sara.ahmadi@example.com',
            'password' => Hash::make('password123'),
            'role' => 'student',
            'phone' => '09131112233',
            'gender' => 'female',
            'birthdate' => '2001-12-10',
            'country' => 'ایران',
            'city' => 'شیراز',
            'timezone' => 'Asia/Tehran',
            'status' => 'deactive',
            'email_verified_at' => now(),
            'phone_verified_at' => now(),
        ]);

        User::create([
            'name' => 'هەژار کەریم',
            'email' => 'hajar.karim@example.com',
            'password' => Hash::make('password123'),
            'role' => 'teacher',
            'phone' => '09194445566',
            'gender' => 'other',
            'birthdate' => '1995-03-25',
            'country' => 'ایران',
            'city' => 'سنندج',
            'timezone' => 'Asia/Tehran',
            'status' => 'suspended',
            'email_verified_at' => now(),
        ]);
    }
}
