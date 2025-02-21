<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use Database\Factories\ConversationFactory;
use Illuminate\Support\Facades\DB;

class Conversation extends Controller
{
    public static function addMessage($role, $body, $chat_id)
    {
        $message = ['role' => $role, 'content' => $body];
        $history = self::history($chat_id);
        $history [] = $message;
        var_dump($history);
        DB::table('conversations')->where('id', $chat_id)->update(['body' => json_encode($history)]);
        Cache::updateHistory($chat_id);
        return $history;
    }



    public static function history($chatId, bool $searchInDB = false)
    {
        if ($searchInDB) return self::historyFromDB($chatId);
        else return self::historyFromCache($chatId);
    }

    private static function historyFromDB($chatId)
    {
        $conversation = Conversation::find($chatId);

        if ($conversation === null) {
            self::createConversation($chatId);
            return [];
        }
        $conversation = Conversation::find($chatId);
        return json_decode($conversation->body, true);
    }

    private static function historyFromCache($chatId)
    {
        $result = Cache::getHistory($chatId);
        if ($result === null) {
            $result = self::historyFromDB($chatId);
        }
        return $result;
    }

    static function createConversation($id)
    {
        return ConversationFactory::new()->custom($id, json_encode([]))->create();
    }
}
