<?php

namespace Database\Seeders;

use App\Models\TicketMessage;
use App\Models\Ticket;
use Illuminate\Database\Seeder;

class TicketMessageSeeder extends Seeder
{
    public function run(): void
    {
        $tickets = Ticket::all();

        if ($tickets->count() === 0) return;

        foreach ($tickets->take(3) as $ticket) {
            // Initial message from user
            TicketMessage::create([
                'ticket_id' => $ticket->id,
                'sender_id' => $ticket->user_id,
                'message' => 'سڵاو، ئەم کێشەیەم هەیە: ' . $ticket->subject . '. تکایە یارمەتیم بدەن.',
            ]);

            // Response from admin/support (if assigned)
            if ($ticket->assigned_to) {
                TicketMessage::create([
                    'ticket_id' => $ticket->id,
                    'sender_id' => $ticket->assigned_to,
                    'message' => 'سڵاو، تکایە زانیاری زیاتر بنێرە بۆ ئەوەی باشتر یارمەتیت بدەین.',
                ]);

                // Follow up from user
                TicketMessage::create([
                    'ticket_id' => $ticket->id,
                    'sender_id' => $ticket->user_id,
                    'message' => 'باشە، ئەمە وردەکارییەکانە...',
                ]);
            }
        }
    }
}
