<?php

use SergiX44\Nutgram\Nutgram;


$bot = new Nutgram(env('TELEGRAM_TOKEN'));
$bot->onCommand('start', function (Nutgram $bot) {
    $bot->sendMessage('Hello, world!');
})->description('The start command!');
$bot->run();
