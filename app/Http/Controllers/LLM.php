<?php

namespace App\Http\Controllers;

use http\Env;
use Illuminate\Support\Facades\Http;
use function PHPUnit\Framework\returnSelf;
use function Symfony\Component\String\s;

class LLM extends Controller
{
    public static function generate(string $prompt, bool $stream = false): string
    {
        $json = Http::post(env('OLLAMA_ADDRESS') . '/api/generate', [
            'model' => env('LLM_MODEL'),
            'prompt' => $prompt,
            'stream' => $stream,
        ]);
        return LLM::generationToResponse($json);
    }

    public static function chat(mixed $chatHistory, $model = null, bool $stream = false): string
    {
        if ($model == null) {
            $model = env('LLM_MODEL');
        }
        var_dump($model);
        $json = Http::post(env('OLLAMA_ADDRESS') . '/api/chat', [
                'model' => $model,
                'messages' => $chatHistory,
                'stream' => $stream,]
        );
        return self::chatToResponse($json);
    }

    public static function models()
    {
        $json = Http::get(env('OLLAMA_ADDRESS') . '/api/tags');

        return self::list($json);
    }

    private static function list($json)
    {
        $array = json_decode($json, true);
        $response = [];
        foreach ($array['models'] as $item) {
            $response[] = $item['name'];
        }
        return $response;
    }

    public static function setModel($id, $name)
    {
        $models = self::models();
        foreach ($models as $model) {

            if ($model === $name) {

                Cache::setHash($id, env('CHAT_HASH_NAME'), 'model', $name);
                return true;
            }
        }
        return false;
    }

    private static function generationToResponse(string $json): string
    {
        $responseArray = json_decode($json, true);
        return $responseArray['response'];
    }

    private static function chatToResponse(string $json): string
    {
        $responseArray = json_decode($json, true);
        var_dump($responseArray);
        return $responseArray['message']['content'];
    }
}
