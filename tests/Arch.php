<?php

test('exceptions')
    ->expect('RAGFlow\Laravel\Exceptions')
    ->toUseNothing();

test('facades')
    ->expect('RAGFlow\Laravel\Facades\RAGFlow')
    ->toOnlyUse([
        'Illuminate\Support\Facades\Facade',
        'RAGFlow\Contracts\ResponseContract',
        'RAGFlow\Laravel\Testing\RAGFlowFake',
        'RAGFlow\Responses\StreamResponse',
    ]);

test('service providers')
    ->expect('RAGFlow\Laravel\ServiceProvider')
    ->toOnlyUse([
        'GuzzleHttp\Client',
        'Illuminate\Support\ServiceProvider',
        'RAGFlow\Laravel',
        'RAGFlow',
        'Illuminate\Contracts\Support\DeferrableProvider',

        // helpers...
        'config',
        'config_path',
    ]);
