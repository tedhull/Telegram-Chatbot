<?php

use App\Http\Controllers\Commands;
use SergiX44\Nutgram\Nutgram;
use App\Http\Controllers\Chat;

$bot = new Nutgram(env('TELEGRAM_TOKEN'));

$bot->onMessage(function (Nutgram $bot) {
    $prompt = $bot->message()->getText();
    $chatId = $bot->chatId();
    $bot->sendMessage(Chat::chat($chatId, $prompt));
});
$bot->onCommand('update', function (Nutgram $bot) {
    Commands::update($bot->chatId());
});
$bot->onCommand('cache', function (Nutgram $bot) {
    $response = Commands::cache($bot->chatId());
    $bot->sendMessage($response);
});

//$bot->sendMessage(text: 'started', chat_id: env('LOG_CHAT_ID'));
//$bot->run();
