<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class LLM extends Controller
{
    public static function generate(string $prompt, bool $stream = false): string
    {
        $json = Http::post('http://localhost:11434/api/generate', [
            'model' => env('LLM_MODEL'),
            'prompt' => $prompt,
            'stream' => $stream,
        ]);
        return LLM::generationToResponse($json);
    }

    public static function chat(string $prompt, mixed $chatHistory, bool $stream = false): string
    {
        $json = Http::post('http://localhost:11434/api/chat', [
                'model' => env('LLM_MODEL'),
                'messages' => $chatHistory,
                'stream' => $stream,]
        );
        return self::chatToResponse($json);
    }

    private static function generationToResponse(string $json): string
    {
        $responseArray = json_decode($json, true);
        return $responseArray['response'];
    }

    private static function chatToResponse(string $json): string
    {
        $responseArray = json_decode($json, true);
        return $responseArray['message']['content'];
    }
}
