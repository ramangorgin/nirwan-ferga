<?php

namespace Database\Seeders;

use App\Models\Payment;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        $enrollments = Enrollment::limit(5)->get();
        $admin = User::where('role', 'admin')->first();

        if ($enrollments->count() === 0) return;

        // Pending payment
        Payment::create([
            'enrollment_id' => $enrollments->first()->id,
            'student_id' => $enrollments->first()->student_id,
            'amount' => 500000,
            'screenshot_path' => 'payments/receipt_001.jpg',
            'status' => 'pending',
        ]);

        // Approved payment
        if ($enrollments->count() > 1) {
            Payment::create([
                'enrollment_id' => $enrollments->get(1)->id,
                'student_id' => $enrollments->get(1)->student_id,
                'amount' => 1500000,
                'screenshot_path' => 'payments/receipt_002.jpg',
                'status' => 'approved',
                'admin_note' => 'پرداخت تایید شد',
                'reviewed_by' => $admin?->id,
                'reviewed_at' => now()->subDays(2),
            ]);
        }

        // Rejected payment
        if ($enrollments->count() > 2) {
            Payment::create([
                'enrollment_id' => $enrollments->get(2)->id,
                'student_id' => $enrollments->get(2)->student_id,
                'amount' => 300000,
                'screenshot_path' => 'payments/receipt_003.jpg',
                'status' => 'rejected',
                'admin_note' => 'رسید نامعتبر است. مجددا ارسال کنید',
                'reviewed_by' => $admin?->id,
                'reviewed_at' => now()->subDay(),
            ]);
        }

        // Another pending
        if ($enrollments->count() > 3) {
            Payment::create([
                'enrollment_id' => $enrollments->get(3)->id,
                'student_id' => $enrollments->get(3)->student_id,
                'amount' => 750000,
                'screenshot_path' => 'payments/receipt_004.jpg',
                'status' => 'pending',
            ]);
        }

        // Approved without note
        if ($enrollments->count() > 4) {
            Payment::create([
                'enrollment_id' => $enrollments->get(4)->id,
                'student_id' => $enrollments->get(4)->student_id,
                'amount' => 200000,
                'screenshot_path' => 'payments/receipt_005.jpg',
                'status' => 'approved',
                'reviewed_by' => $admin?->id,
                'reviewed_at' => now()->subHours(5),
            ]);
        }
    }
}
