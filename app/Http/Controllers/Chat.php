<?php
namespace App\Http\Controllers;


class Chat extends Controller
{
    public static function chat($chatId, $prompt)
    {
        $history = ConversationController::addMessage('user', $prompt, $chatId);
        $response = LLM::chat( $history);
        ConversationController::addMessage('assistant', $response, $chatId);
        return $response;
    }
}
