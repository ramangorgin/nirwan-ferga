<?php

namespace Database\Seeders;

use App\Models\DiscountCode;
use App\Models\User;
use Illuminate\Database\Seeder;

class DiscountCodeSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();
        $student = User::where('role', 'student')->first();

        DiscountCode::create([
            'code' => 'NOWRUZ1403',
            'percentage' => 20,
            'max_uses' => 50,
            'user_id' => null, // Public discount
            'expires_at' => now()->addMonths(3),
            'active' => true,
        ]);

        DiscountCode::create([
            'code' => 'STUDENT50',
            'percentage' => 50,
            'max_uses' => 10,
            'user_id' => null,
            'expires_at' => now()->addMonth(),
            'active' => true,
        ]);

        DiscountCode::create([
            'code' => 'PRIVATE100',
            'percentage' => 100,
            'max_uses' => 1,
            'user_id' => $student?->id,
            'expires_at' => now()->addWeek(),
            'active' => true,
        ]);

        DiscountCode::create([
            'code' => 'EXPIRED10',
            'percentage' => 10,
            'max_uses' => 100,
            'user_id' => null,
            'expires_at' => now()->subDays(5),
            'active' => true,
        ]);

        DiscountCode::create([
            'code' => 'INACTIVE30',
            'percentage' => 30,
            'max_uses' => 20,
            'user_id' => null,
            'expires_at' => now()->addMonth(),
            'active' => false,
        ]);
    }
}
