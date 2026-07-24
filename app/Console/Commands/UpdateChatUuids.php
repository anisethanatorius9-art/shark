<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Chat;
use Illuminate\Support\Str;

class UpdateChatUuids extends Command
{
    protected $signature = 'chats:update-uuids';
    protected $description = 'Update existing chats with UUIDs';

    public function handle()
    {
        $chats = Chat::whereNull('uuid')->get();

        foreach ($chats as $chat) {
            $chat->uuid = (string) Str::uuid();
            $chat->save();
            $this->info("Updated chat ID {$chat->id} with UUID {$chat->uuid}");
        }

        $this->info('Done! Updated ' . $chats->count() . ' chats.');

        return Command::SUCCESS;
    }
}
