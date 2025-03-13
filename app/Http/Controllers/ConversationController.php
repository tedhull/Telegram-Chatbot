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

        $history = self::history($chat_id);
        $history [] = $message;

        DB::table('conversations')->where('id', $chat_id)->update(['body' => json_encode($history)]);
        Cache::setHash($chat_id, env('CHAT_HASH_NAME'), 'history', $history);

        return $history;
    }


    public static function history($chatId, bool $searchInDB = false)
    {
        if ($searchInDB) return self::historyFromDB($chatId);
        else return self::historyFromCache($chatId);
    }

    private static function historyFromDB($chatId)
    {
        try {
            $conversation = Conversation::find($chatId);
            return json_decode($conversation->body, true);
        } catch (\Exception $e) {
            self::createConversation($chatId);
            return [];
        }
    }

    private static function historyFromCache($chatId)
    {
        $result = Cache::getHash($chatId, env('CHAT_HASH_NAME'), 'history');
        if ($result === null) {
            $result = self::historyFromDB($chatId);
        }
        return $result;
    }

    static function createConversation($id)
    {
        $model = env('LLM_MODEL');
        var_dump($model);
        $conversation = ConversationFactory::new()->custom($id, json_encode([]), $model)->create();
        Cache::setHash($id, env('CHAT_HASH_NAME'), 'model', $model);
        return $conversation;
    }
}
