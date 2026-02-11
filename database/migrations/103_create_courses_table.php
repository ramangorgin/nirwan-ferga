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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();

            // Advertisements
            $table->string('title');
            $table->text('description');
            $table->string('video_path')->nullable();
            $table->string('poster_path')->nullable();

            // Details of teaching
            $table->enum('level', ['beginner' , 'intermediate', 'advanced', 'free']);
            $table->boolean('teaching_in_kurdish');
            $table->unsignedInteger('capacity_min');
            $table->unsignedInteger('capacity_max');
            $table->foreignId('teacher_id')->nullable()->constrained('users')->nullOnDelete();            
            $table->dateTime('registration_deadline');
            $table->date('start_date');
            $table->date('end_date');
            $table->json('days_of_week', ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday']);
            $table->time('start_time');
            $table->unsignedSmallInteger('session_duration'); //minutes
            $table->integer('sessions_count');
            $table->json('syllabus');

            // Price
            $table->unsignedInteger('price');
            $table->string('card_number')->nullable();
            $table->string('card_shaba_number')->nullable();
            $table->string('card_owner_name')->nullable();
            $table->string('bank_name')->nullable();

            // Statuses
            $table->boolean('is_active')->default(true);
            $table->enum('status' , ['cancelled', 'ongoing', 'full', 'finished', 'registration_open'])->default('registration_open');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
