<?php

use Illuminate\Config\Repository;
use RAGFlow\Laravel\Facades\RAGFlow;
use RAGFlow\Laravel\ServiceProvider;
use RAGFlow\Resources\Completions;
use RAGFlow\Responses\Completions\CreateResponse;
use PHPUnit\Framework\ExpectationFailedException;

it('resolves resources', function () {
    $app = app();

    $app->bind('config', fn () => new Repository([
        'ragflow' => [
            'api_key' => 'test',
        ],
    ]));

    (new ServiceProvider($app))->register();

    RAGFlow::setFacadeApplication($app);

    $completions = RAGFlow::completions();

    expect($completions)->toBeInstanceOf(Completions::class);
});

test('fake returns the given response', function () {
    RAGFlow::fake([
        CreateResponse::fake([
            'choices' => [
                [
                    'text' => 'awesome!',
                ],
            ],
        ]),
    ]);

    $completion = RAGFlow::completions()->create([
        'model' => 'gpt-3.5-turbo-instruct',
        'prompt' => 'PHP is ',
    ]);

    expect($completion['choices'][0]['text'])->toBe('awesome!');
});

test('fake throws an exception if there is no more given response', function () {
    RAGFlow::fake([
        CreateResponse::fake(),
    ]);

    RAGFlow::completions()->create([
        'model' => 'gpt-3.5-turbo-instruct',
        'prompt' => 'PHP is ',
    ]);

    RAGFlow::completions()->create([
        'model' => 'gpt-3.5-turbo-instruct',
        'prompt' => 'PHP is ',
    ]);
})->expectExceptionMessage('No fake responses left');

test('append more fake responses', function () {
    RAGFlow::fake([
        CreateResponse::fake([
            'id' => 'cmpl-1',
        ]),
    ]);

    RAGFlow::addResponses([
        CreateResponse::fake([
            'id' => 'cmpl-2',
        ]),
    ]);

    $completion = RAGFlow::completions()->create([
        'model' => 'gpt-3.5-turbo-instruct',
        'prompt' => 'PHP is ',
    ]);

    expect($completion)
        ->id->toBe('cmpl-1');

    $completion = RAGFlow::completions()->create([
        'model' => 'gpt-3.5-turbo-instruct',
        'prompt' => 'PHP is ',
    ]);

    expect($completion)
        ->id->toBe('cmpl-2');
});

test('fake can assert a request was sent', function () {
    RAGFlow::fake([
        CreateResponse::fake(),
    ]);

    RAGFlow::completions()->create([
        'model' => 'gpt-3.5-turbo-instruct',
        'prompt' => 'PHP is ',
    ]);

    RAGFlow::assertSent(Completions::class, function (string $method, array $parameters): bool {
        return $method === 'create' &&
            $parameters['model'] === 'gpt-3.5-turbo-instruct' &&
            $parameters['prompt'] === 'PHP is ';
    });
});

test('fake throws an exception if a request was not sent', function () {
    RAGFlow::fake([
        CreateResponse::fake(),
    ]);

    RAGFlow::assertSent(Completions::class, function (string $method, array $parameters): bool {
        return $method === 'create' &&
            $parameters['model'] === 'gpt-3.5-turbo-instruct' &&
            $parameters['prompt'] === 'PHP is ';
    });
})->expectException(ExpectationFailedException::class);

test('fake can assert a request was sent on the resource', function () {
    RAGFlow::fake([
        CreateResponse::fake(),
    ]);

    RAGFlow::completions()->create([
        'model' => 'gpt-3.5-turbo-instruct',
        'prompt' => 'PHP is ',
    ]);

    RAGFlow::completions()->assertSent(function (string $method, array $parameters): bool {
        return $method === 'create' &&
            $parameters['model'] === 'gpt-3.5-turbo-instruct' &&
            $parameters['prompt'] === 'PHP is ';
    });
});

test('fake can assert a request was sent n times', function () {
    RAGFlow::fake([
        CreateResponse::fake(),
        CreateResponse::fake(),
    ]);

    RAGFlow::completions()->create([
        'model' => 'gpt-3.5-turbo-instruct',
        'prompt' => 'PHP is ',
    ]);

    RAGFlow::completions()->create([
        'model' => 'gpt-3.5-turbo-instruct',
        'prompt' => 'PHP is ',
    ]);

    RAGFlow::assertSent(Completions::class, 2);
});

test('fake throws an exception if a request was not sent n times', function () {
    RAGFlow::fake([
        CreateResponse::fake(),
        CreateResponse::fake(),
    ]);

    RAGFlow::completions()->create([
        'model' => 'gpt-3.5-turbo-instruct',
        'prompt' => 'PHP is ',
    ]);

    RAGFlow::assertSent(Completions::class, 2);
})->expectException(ExpectationFailedException::class);

test('fake can assert a request was not sent', function () {
    RAGFlow::fake();

    RAGFlow::assertNotSent(Completions::class);
});

test('fake throws an exception if a unexpected request was sent', function () {
    RAGFlow::fake([
        CreateResponse::fake(),
    ]);

    RAGFlow::completions()->create([
        'model' => 'gpt-3.5-turbo-instruct',
        'prompt' => 'PHP is ',
    ]);

    RAGFlow::assertNotSent(Completions::class);
})->expectException(ExpectationFailedException::class);

test('fake can assert a request was not sent on the resource', function () {
    RAGFlow::fake([
        CreateResponse::fake(),
    ]);

    RAGFlow::completions()->assertNotSent();
});

test('fake can assert no request was sent', function () {
    RAGFlow::fake();

    RAGFlow::assertNothingSent();
});

test('fake throws an exception if any request was sent when non was expected', function () {
    RAGFlow::fake([
        CreateResponse::fake(),
    ]);

    RAGFlow::completions()->create([
        'model' => 'gpt-3.5-turbo-instruct',
        'prompt' => 'PHP is ',
    ]);

    RAGFlow::assertNothingSent();
})->expectException(ExpectationFailedException::class);
