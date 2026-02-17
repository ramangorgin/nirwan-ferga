<?php

namespace App\Services\Sms;

use App\Models\User;
use Illuminate\Support\Facades\Log;

class SmsService
{
    /**
     * Send SMS to a user by ID
     * 
     * @param int $userId User ID
     * @param string $message Message to send
     * @return bool
     */
    public function sendToUserId(int $userId, string $message): bool
    {
        $user = User::find($userId);
        
        if (!$user || !$user->phone) {
            Log::warning("Cannot send SMS to user {$userId}: user not found or no phone number");
            return false;
        }

        return $this->send($user->phone, $message);
    }

    /**
     * Send SMS to a phone number
     * 
     * @param string $phoneNumber Phone number
     * @param string $message Message to send
     * @return bool
     */
    public function send(string $phoneNumber, string $message): bool
    {
        try {
            // TODO: Integrate with your SMS provider (Twilio, Kavenegar, etc.)
            // Example for Kavenegar:
            // $client = new \Kavenegar\Laravel\Facade\Kavenegar();
            // $client->send($phoneNumber, $message);

            Log::info("SMS sent to {$phoneNumber}: {$message}");
            return true;
        } catch (\Exception $e) {
            Log::error("Failed to send SMS to {$phoneNumber}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Send SMS to multiple phone numbers
     * 
     * @param array $phoneNumbers Array of phone numbers
     * @param string $message Message to send
     * @return bool
     */
    public function sendToMany(array $phoneNumbers, string $message): bool
    {
        $success = true;

        foreach ($phoneNumbers as $phoneNumber) {
            if (!$this->send($phoneNumber, $message)) {
                $success = false;
            }
        }

        return $success;
    }
}
