<?php

declare(strict_types=1);

namespace OpenRouter\Laravel\Facades;

use Illuminate\Support\Facades\Facade;
use OpenAI\Contracts\ResponseContract;
use OpenAI\Responses\StreamResponse;
use OpenRouter\Laravel\Testing\OpenRouterFake;

/**
 * @method static \OpenAI\Resources\Assistants assistants()
 * @method static \OpenAI\Resources\Audio audio()
 * @method static \OpenAI\Resources\Batches batches()
 * @method static \OpenAI\Resources\Chat chat()
 * @method static \OpenAI\Resources\Completions completions()
 * @method static \OpenAI\Resources\Containers containers()
 * @method static \OpenAI\Resources\Embeddings embeddings()
 * @method static \OpenAI\Resources\Edits edits()
 * @method static \OpenAI\Resources\Files files()
 * @method static \OpenAI\Resources\FineTunes fineTunes()
 * @method static \OpenAI\Resources\FineTuning fineTuning()
 * @method static \OpenAI\Resources\Images images()
 * @method static \OpenAI\Resources\Models models()
 * @method static \OpenAI\Resources\Moderations moderations()
 * @method static \OpenAI\Resources\Realtime realtime()
 * @method static \OpenAI\Resources\Responses responses()
 * @method static \OpenAI\Resources\Threads threads()
 * @method static \OpenAI\Resources\VectorStores vectorStores()
 */
final class OpenRouter extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return 'openrouter';
    }

    /**
     * @param  array<array-key, ResponseContract|StreamResponse|string>  $responses
     */
    public static function fake(array $responses = []): OpenRouterFake /** @phpstan-ignore-line */
    {
        $fake = new OpenRouterFake($responses);
        self::swap($fake);

        return $fake;
    }
}
