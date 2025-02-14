<?php

use SergiX44\Nutgram\Nutgram;
use App\Http\Controllers\ChatController;
$bot = new Nutgram(env('TELEGRAM_TOKEN'));
$bot->onCommand('start', function (Nutgram $bot) {
    $bot->sendMessage('Hello, world!');
})->description('The start command!');
$bot->onMessage(function (Nutgram $bot) {
    $prompt = $bot->message()->getText();
    $chatId = $bot->chatId();
    $bot->sendMessage(ChatController::chat($chatId, $prompt));
});
$bot->run();
