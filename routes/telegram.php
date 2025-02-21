<?php

use App\Http\Controllers\Commands;
use SergiX44\Nutgram\Nutgram;
use App\Http\Controllers\Chat;

$bot = new Nutgram(env('TELEGRAM_TOKEN'));
$bot->onCommand('start', function (Nutgram $bot) {
    $bot->sendMessage('Hello, world!');
})->description('The start command!');

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
$bot->run();
