<?php

namespace Database\Seeders;

use App\Models\Message;
use App\Models\Conversation;
use Illuminate\Database\Seeder;

class MessageSeeder extends Seeder
{
    public function run(): void
    {
        $conversations = Conversation::all();

        if ($conversations->count() === 0) return;

        foreach ($conversations->take(2) as $conversation) {
            // Message from student
            Message::create([
                'conversation_id' => $conversation->id,
                'sender_id' => $conversation->student_id,
                'message' => 'سڵاو مامۆستا، پرسیارێکم هەیە سەبارەت بە وانەکە',
                'is_read' => true,
                'read_at' => now()->subHours(2),
            ]);

            // Response from teacher
            Message::create([
                'conversation_id' => $conversation->id,
                'sender_id' => $conversation->teacher_id,
                'message' => 'سڵاو، بڵێ پرسیارەکەت چییە؟ پێم بڵێ یارمەتیت بدەم',
                'is_read' => false,
                'read_at' => null,
            ]);

            // Another message from student
            Message::create([
                'conversation_id' => $conversation->id,
                'sender_id' => $conversation->student_id,
                'message' => 'ئایا دەتوانم ئەرکەکە دواتر بنێرم؟',
                'is_read' => false,
                'read_at' => null,
            ]);
        }
    }
}
