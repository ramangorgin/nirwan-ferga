<?php

namespace Database\Seeders;

use App\Models\SessionMaterial;
use App\Models\ClassSession;
use App\Models\User;
use Illuminate\Database\Seeder;

class SessionMaterialSeeder extends Seeder
{
    public function run(): void
    {
        $sessions = ClassSession::where('has_materials', true)->get();
        $teacher = User::where('role', 'teacher')->first();

        if ($sessions->count() === 0) return;

        foreach ($sessions->take(2) as $session) {
            SessionMaterial::create([
                'session_id' => $session->id,
                'file_path' => 'materials/session-' . $session->id . '/presentation.pdf',
                'file_type' => 'pdf',
                'title' => 'پێشکەشکردنی وانە',
                'description' => 'فایلی PDF ی وانەکە',
                'uploaded_by' => $teacher?->id,
                'visibility' => 'students_only',
            ]);

            SessionMaterial::create([
                'session_id' => $session->id,
                'file_path' => 'materials/session-' . $session->id . '/video.mp4',
                'file_type' => 'video',
                'title' => 'ڤیدیۆی تۆمارکراوی وانە',
                'description' => 'تۆمارکردنی وانەکە بۆ دواتر',
                'uploaded_by' => $teacher?->id,
                'visibility' => 'public',
            ]);

            SessionMaterial::create([
                'session_id' => $session->id,
                'file_path' => 'materials/session-' . $session->id . '/audio.mp3',
                'file_type' => 'audio',
                'title' => 'فایلی دەنگی وانە',
                'uploaded_by' => $teacher?->id,
                'visibility' => 'hidden',
            ]);
        }
    }
}
