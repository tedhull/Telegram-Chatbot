<?php

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
});
Route::get('/test', function () {
    echo(Http::post('http://localhost:11434/api/generate', ['model' => 'llama3.2:1b', 'prompt' => 'Why is the sky blue?']));
});
Route::get('/welcome', function () {
    require view('welcome');
});
