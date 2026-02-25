<?php

namespace Database\Seeders;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Seeder;

class TicketSeeder extends Seeder
{
    public function run(): void
    {
        $students = User::where('role', 'student')->get();
        $admin = User::where('role', 'admin')->first();

        if ($students->count() === 0) return;

        // Open ticket - low priority
        Ticket::create([
            'user_id' => $students->first()->id,
            'subject' => 'پرسیار دەربارەی کۆرسەکان',
            'priority' => 'low',
            'status' => 'open',
        ]);

        // In progress ticket - medium priority
        if ($students->count() > 1) {
            Ticket::create([
                'user_id' => $students->get(1)->id,
                'subject' => 'کێشەی پارەدان',
                'priority' => 'medium',
                'status' => 'in_progress',
                'assigned_to' => $admin?->id,
            ]);
        }

        // Answered ticket - high priority
        Ticket::create([
            'user_id' => $students->first()->id,
            'subject' => 'کێشە لە چوونەژوورەوە',
            'priority' => 'high',
            'status' => 'answered',
            'assigned_to' => $admin?->id,
        ]);

        // Closed ticket - medium priority
        if ($students->count() > 1) {
            Ticket::create([
                'user_id' => $students->get(1)->id,
                'subject' => 'داواکاری گۆڕینی ئیمەیڵ',
                'priority' => 'medium',
                'status' => 'closed',
                'assigned_to' => $admin?->id,
            ]);
        }

        // Open ticket without assignment - high priority
        Ticket::create([
            'user_id' => $students->first()->id,
            'subject' => 'مشکلی فنی در سایت',
            'priority' => 'high',
            'status' => 'open',
        ]);
    }
}
