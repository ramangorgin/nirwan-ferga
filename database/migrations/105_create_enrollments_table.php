<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('student_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('course_id')->constrained('courses')->restrictOnDelete();
            $table->unique(['student_id', 'course_id']);

            $table->enum('status', [
                'pending', 
                'waiting_list', 
                'confirmed', 
                'rejected', 
                'cancelled', 
                'completed'
            ])->default('pending');

            $table->enum('payment_status', ['unpaid', 'partial', 'paid', 'refunded'])
                ->default('unpaid');

            $table->unsignedInteger('paid_amount')->default(0);

            $table->string('payment_screenshot_path')->nullable();

            $table->unsignedInteger('final_score')->nullable();
            $table->boolean('certificate_issued')->default(false);

            $table->timestamp('enrolled_at')->useCurrent();

            $table->foreignId('discount_code_id')
                ->nullable()
                ->constrained('discount_codes')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};
