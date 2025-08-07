<?php

use Illuminate\Config\Repository;
use OpenAI\Resources\Completions;
use OpenAI\Responses\Completions\CreateResponse;
use OpenRouter\Laravel\Facades\OpenRouter;
use OpenRouter\Laravel\ServiceProvider;
use PHPUnit\Framework\ExpectationFailedException;

it('resolves resources', function () {
    $app = app();

    $app->bind('config', fn () => new Repository([
        'openrouter' => [
            'api_key' => 'test',
        ],
    ]));

    (new ServiceProvider($app))->register();

    OpenRouter::setFacadeApplication($app);

    $completions = OpenRouter::completions();

    expect($completions)->toBeInstanceOf(Completions::class);
});

test('fake returns the given response', function () {
    OpenRouter::fake([
        CreateResponse::fake([
            'choices' => [
                [
                    'text' => 'awesome!',
                ],
            ],
        ]),
    ]);

    $completion = OpenRouter::completions()->create([
        'model' => 'gpt-3.5-turbo-instruct',
        'prompt' => 'PHP is ',
    ]);

    expect($completion['choices'][0]['text'])->toBe('awesome!');
});

test('fake throws an exception if there is no more given response', function () {
    OpenRouter::fake([
        CreateResponse::fake(),
    ]);

    OpenRouter::completions()->create([
        'model' => 'gpt-3.5-turbo-instruct',
        'prompt' => 'PHP is ',
    ]);

    OpenRouter::completions()->create([
        'model' => 'gpt-3.5-turbo-instruct',
        'prompt' => 'PHP is ',
    ]);
})->expectExceptionMessage('No fake responses left');

test('append more fake responses', function () {
    OpenRouter::fake([
        CreateResponse::fake([
            'id' => 'cmpl-1',
        ]),
    ]);

    OpenRouter::addResponses([
        CreateResponse::fake([
            'id' => 'cmpl-2',
        ]),
    ]);

    $completion = OpenRouter::completions()->create([
        'model' => 'gpt-3.5-turbo-instruct',
        'prompt' => 'PHP is ',
    ]);

    expect($completion)
        ->id->toBe('cmpl-1');

    $completion = OpenRouter::completions()->create([
        'model' => 'gpt-3.5-turbo-instruct',
        'prompt' => 'PHP is ',
    ]);

    expect($completion)
        ->id->toBe('cmpl-2');
});

test('fake can assert a request was sent', function () {
    OpenRouter::fake([
        CreateResponse::fake(),
    ]);

    OpenRouter::completions()->create([
        'model' => 'gpt-3.5-turbo-instruct',
        'prompt' => 'PHP is ',
    ]);

    OpenRouter::assertSent(Completions::class, function (string $method, array $parameters): bool {
        return $method === 'create' &&
            $parameters['model'] === 'gpt-3.5-turbo-instruct' &&
            $parameters['prompt'] === 'PHP is ';
    });
});

test('fake throws an exception if a request was not sent', function () {
    OpenRouter::fake([
        CreateResponse::fake(),
    ]);

    OpenRouter::assertSent(Completions::class, function (string $method, array $parameters): bool {
        return $method === 'create' &&
            $parameters['model'] === 'gpt-3.5-turbo-instruct' &&
            $parameters['prompt'] === 'PHP is ';
    });
})->expectException(ExpectationFailedException::class);

test('fake can assert a request was sent on the resource', function () {
    OpenRouter::fake([
        CreateResponse::fake(),
    ]);

    OpenRouter::completions()->create([
        'model' => 'gpt-3.5-turbo-instruct',
        'prompt' => 'PHP is ',
    ]);

    OpenRouter::completions()->assertSent(function (string $method, array $parameters): bool {
        return $method === 'create' &&
            $parameters['model'] === 'gpt-3.5-turbo-instruct' &&
            $parameters['prompt'] === 'PHP is ';
    });
});

test('fake can assert a request was sent n times', function () {
    OpenRouter::fake([
        CreateResponse::fake(),
        CreateResponse::fake(),
    ]);

    OpenRouter::completions()->create([
        'model' => 'gpt-3.5-turbo-instruct',
        'prompt' => 'PHP is ',
    ]);

    OpenRouter::completions()->create([
        'model' => 'gpt-3.5-turbo-instruct',
        'prompt' => 'PHP is ',
    ]);

    OpenRouter::assertSent(Completions::class, 2);
});

test('fake throws an exception if a request was not sent n times', function () {
    OpenRouter::fake([
        CreateResponse::fake(),
        CreateResponse::fake(),
    ]);

    OpenRouter::completions()->create([
        'model' => 'gpt-3.5-turbo-instruct',
        'prompt' => 'PHP is ',
    ]);

    OpenRouter::assertSent(Completions::class, 2);
})->expectException(ExpectationFailedException::class);

test('fake can assert a request was not sent', function () {
    OpenRouter::fake();

    OpenRouter::assertNotSent(Completions::class);
});

test('fake throws an exception if a unexpected request was sent', function () {
    OpenRouter::fake([
        CreateResponse::fake(),
    ]);

    OpenRouter::completions()->create([
        'model' => 'gpt-3.5-turbo-instruct',
        'prompt' => 'PHP is ',
    ]);

    OpenRouter::assertNotSent(Completions::class);
})->expectException(ExpectationFailedException::class);

test('fake can assert a request was not sent on the resource', function () {
    OpenRouter::fake([
        CreateResponse::fake(),
    ]);

    OpenRouter::completions()->assertNotSent();
});

test('fake can assert no request was sent', function () {
    OpenRouter::fake();

    OpenRouter::assertNothingSent();
});

test('fake throws an exception if any request was sent when non was expected', function () {
    OpenRouter::fake([
        CreateResponse::fake(),
    ]);

    OpenRouter::completions()->create([
        'model' => 'gpt-3.5-turbo-instruct',
        'prompt' => 'PHP is ',
    ]);

    OpenRouter::assertNothingSent();
})->expectException(ExpectationFailedException::class);
