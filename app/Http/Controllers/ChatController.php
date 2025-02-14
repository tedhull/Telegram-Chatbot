<?php

namespace App\Http\Controllers;


use App\Models\Conversation;

class ChatController extends Controller
{
    public static function chat($chatId, $prompt)
    {
        ConversationController::addMessage('user', $prompt, $chatId);
        $conversationHistory = ConversationController::history($chatId);
        $response = LLM::chat($prompt, $conversationHistory);
        ConversationController::addMessage('assistant', $response, $chatId);
        return $response;
    }
}
