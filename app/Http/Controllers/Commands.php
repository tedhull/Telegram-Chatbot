<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Commands extends Controller
{
    public static function cache($chatId)
    {
        return json_encode(Cache::getHistory($chatId));
    }

    public static function update($chatId)
    {
        Cache::updateHistory($chatId);
    }
}
