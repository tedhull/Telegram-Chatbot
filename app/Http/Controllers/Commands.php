<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use Illuminate\Http\Request;

class Commands extends Controller
{
    public static function cache($chatId)
    {
        return json_encode(ConversationController::history($chatId));
    }

    public static function update($chatId)
    {
        Cache::updateHistory($chatId);
    }
}
