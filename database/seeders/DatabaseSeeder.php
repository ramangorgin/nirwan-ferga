<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Run all seeders in dependency order
        $this->call([
            // Base tables (no dependencies)
            UserSeeder::class,
            DiscountCodeSeeder::class,
            
            // Course related (depends on User)
            CourseSeeder::class,
            ClassSessionSeeder::class,
            
            // Enrollment and related (depends on Course, User, DiscountCode)
            EnrollmentSeeder::class,
            SessionMaterialSeeder::class,
            
            // Assignments and Submissions (depends on ClassSession, Enrollment)
            AssignmentSeeder::class,
            AssignmentPersonalizationSeeder::class,
            SubmissionSeeder::class,
            AttendanceSeeder::class,
            
            // Quizzes (depends on Course, User)
            QuizSeeder::class,
            QuizQuestionSeeder::class,
            QuizSubmissionSeeder::class,
            QuizAnswerSeeder::class,
            
            // Payments (depends on Enrollment, User)
            PaymentSeeder::class,
            
            // Communication (depends on User, Course)
            ConversationSeeder::class,
            MessageSeeder::class,
            TicketSeeder::class,
            TicketMessageSeeder::class,
            
            // Content (depends on User, Course)
            AnnouncementSeeder::class,
            PostSeeder::class,
            NotificationSeeder::class,
        ]);

        $this->command->info('✅ All seeders completed successfully!');
        $this->command->newLine();
        $this->command->info('📝 Default Admin Credentials:');
        $this->command->info('   Email: raman.gorginpaveh@gmail.com');
        $this->command->info('   Password: 2751');
        $this->command->newLine();
        $this->command->warn('⚠️  Avatar file location:');
        $this->command->warn('   Place your avatar at: storage/app/public/avatars/1/avatar.jpg');
        $this->command->warn('   Then run: php artisan storage:link');
    }
}
