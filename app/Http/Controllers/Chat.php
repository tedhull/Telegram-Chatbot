<?php

namespace App\Http\Controllers;


class Chat extends Controller
{
    public static function chat($chatId, $prompt)
    {
        $history = ConversationController::addMessage('user', $prompt, $chatId);
        $model = Cache::getHash($chatId, env('CHAT_HASH_NAME'), 'model');
        $response = LLM::chat($history,$model);
        ConversationController::addMessage('assistant', $response, $chatId);
        return $response;
    }
}
