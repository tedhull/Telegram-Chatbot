<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Redis;
use Illuminate\Http\Request;
use SergiX44\Nutgram\Conversations\Conversation;

class Cache extends Controller
{
    public static function updateHistory($chatId)
    {
        $data = ConversationController::history($chatId, true);
        self::setHash($chatId, env('CHAT_HASH_NAME'), 'history', $data);
    }

    public static function setHash($id, $name, $key, $value)
    {
        $redis = new \Redis();
        $redis->connect(env('REDIS_HOST'), env('REDIS_PORT'));
        $hashName = $name . ':' . $id;
        $redis->hSet($hashName, $key, json_encode($value));
    }

    public static function getHash($id, $name, $key)
    {

        $redis = new \Redis();
        $redis->connect(env('REDIS_HOST'), env('REDIS_PORT'));
        $hashName = $name . ':' . $id;
        return json_decode($redis->hGet($hashName, $key), true);
    }
}
