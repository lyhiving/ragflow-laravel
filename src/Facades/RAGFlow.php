<?php

declare(strict_types=1);

namespace RAGFlow\Laravel\Facades;

use Illuminate\Support\Facades\Facade;
use RAGFlow\Contracts\ResponseContract;
use RAGFlow\Laravel\Testing\RAGFlowFake;
use RAGFlow\Responses\StreamResponse;

/**
 * @method static \RAGFlow\Resources\Agents agents()
 * @method static \RAGFlow\Resources\Chats chats()
 * @method static \RAGFlow\Resources\Completions completions()
 * @method static \RAGFlow\Resources\Files files()
 * @method static \RAGFlow\Resources\Chunks chunks()
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
