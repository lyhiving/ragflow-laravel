<?php

declare(strict_types=1);

namespace RAGFlow\Laravel\Facades;

use Illuminate\Support\Facades\Facade;
use RAGFlow\Contracts\ResponseContract;
use RAGFlow\Laravel\Testing\RAGFlowFake;
use RAGFlow\Responses\StreamResponse;

/**
 * @method static \RAGFlow\Resources\Assistants assistants()
 * @method static \RAGFlow\Resources\Audio audio()
 * @method static \RAGFlow\Resources\Batches batches()
 * @method static \RAGFlow\Resources\Chat chat()
 * @method static \RAGFlow\Resources\Completions completions()
 * @method static \RAGFlow\Resources\Embeddings embeddings()
 * @method static \RAGFlow\Resources\Edits edits()
 * @method static \RAGFlow\Resources\Files files()
 * @method static \RAGFlow\Resources\Chunks chunks()
 * @method static \RAGFlow\Resources\FineTunes fineTunes()
 * @method static \RAGFlow\Resources\Images images()
 * @method static \RAGFlow\Resources\Models models()
 * @method static \RAGFlow\Resources\Moderations moderations()
 * @method static \RAGFlow\Resources\Threads threads()
 * @method static \RAGFlow\Resources\VectorStores vectorStores()
 */
final class RAGFlow extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return 'ragflow';
    }

    /**
     * @param  array<array-key, ResponseContract|StreamResponse|string>  $responses
     */
    public static function fake(array $responses = []): RAGFlowFake /** @phpstan-ignore-line */
    {
        $fake = new RAGFlowFake($responses);
        self::swap($fake);

        return $fake;
    }
}
