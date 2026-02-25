<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use App\Models\DiscountCode;
use Illuminate\Database\Seeder;

class EnrollmentSeeder extends Seeder
{
    public function run(): void
    {
        $students = User::where('role', 'student')->get();
        $courses = Course::take(3)->get();
        $discount = DiscountCode::where('active', true)->first();

        if ($students->count() > 0 && $courses->count() > 0) {
            Enrollment::create([
                'student_id' => $students[0]->id,
                'course_id' => $courses[0]->id,
                'status' => 'pending',
                'payment_status' => 'unpaid',
                'paid_amount' => 0,
                'discount_code_id' => null,
                'enrolled_at' => now(),
            ]);

            Enrollment::create([
                'student_id' => $students[0]->id,
                'course_id' => $courses[1]->id ?? $courses[0]->id,
                'status' => 'confirmed',
                'payment_status' => 'paid',
                'paid_amount' => 1500000,
                'discount_code_id' => $discount?->id,
                'enrolled_at' => now()->subDays(5),
            ]);

            if ($students->count() > 1) {
                Enrollment::create([
                    'student_id' => $students[1]->id,
                    'course_id' => $courses[0]->id,
                    'status' => 'rejected',
                    'payment_status' => 'unpaid',
                    'paid_amount' => 0,
                    'enrolled_at' => now()->subDays(3),
                ]);

                Enrollment::create([
                    'student_id' => $students[1]->id,
                    'course_id' => $courses[1]->id ?? $courses[0]->id,
                    'status' => 'completed',
                    'payment_status' => 'paid',
                    'paid_amount' => 1500000,
                    'final_score' => 85,
                    'certificate_issued' => true,
                    'enrolled_at' => now()->subMonths(2),
                ]);
            }

            Enrollment::create([
                'student_id' => $students[0]->id,
                'course_id' => $courses[2]->id ?? $courses[0]->id,
                'status' => 'cancelled',
                'payment_status' => 'refunded',
                'paid_amount' => 0,
                'enrolled_at' => now()->subWeek(),
            ]);
        }
    }
}
