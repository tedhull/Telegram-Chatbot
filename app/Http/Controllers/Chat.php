<?php
namespace App\Http\Controllers;


class Chat extends Controller
{
    public static function chat($chatId, $prompt)
    {
        $history = Conversation::addMessage('user', $prompt, $chatId);
        $response = LLM::chat( $history);
        Conversation::addMessage('assistant', $response, $chatId);
        return $response;
    }
}
