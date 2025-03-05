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

    public static function model($chatId, $name)
    {
        $result = LLM::setModel($chatId, $name);

        if ($result) {
            return "used model is now {$name}";
        }
        else return "model with this name not found, try to request other name";
    }

    public static function models()
    {
        return LLM::models();
    }

    public static function update($chatId)
    {
        Cache::updateHistory($chatId);
    }
}
