<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use Database\Factories\ConversationFactory;
use Illuminate\Support\Facades\DB;

class ConversationController extends Controller
{
    public static function addMessage($role, $body, $chat_id)
    {
        $message = ['role' => $role, 'content' => $body];

        $conversation = Conversation::find($chat_id);

        if ($conversation === null) {
            $conversation = self::createConversation($chat_id);
        }
        $conversationHistory = $conversation->body;
        $history = json_decode($conversationHistory, true);
        $history['body'] [] = $message;
        DB::table('conversations')->where('id', $chat_id)->update(['body' => json_encode($history)]);
        return;

        //$history = ['body' => [$message]];
        //  DB::table('conversations')->where('id', $chat_id)->update(['body' => json_encode($history)]);
    }

    public static function history($chatId)
    {
        $conversation = Conversation::find($chatId);
        $history = json_decode($conversation->body, true);
        return $history['body'];
    }

    static function createConversation($id)
    {
        return ConversationFactory::new()->custom($id, json_encode(['body' => []]))->create();
    }
}
