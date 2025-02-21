<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Redis;
use Illuminate\Http\Request;
use SergiX44\Nutgram\Conversations\Conversation;

class Cache extends Controller
{
    public static function updateHistory($chatId)
    {
        $key = 'chat:' . $chatId;
        $data = Conversation::history($chatId, true);
        Redis::command("set", [$key, json_encode($data)]);
    }

    public static function getHistory($chatId)
    {
        $key = 'chat:' . $chatId;
        $data = Redis::command('get', [$key]);
        return json_decode($data, true);
    }
}
