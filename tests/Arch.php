<?php

test('exceptions')
    ->expect('OpenRouter\Laravel\Exceptions')
    ->toUseNothing();

test('facades')
    ->expect('OpenRouter\Laravel\Facades\OpenRouter')
    ->toOnlyUse([
        'Illuminate\Support\Facades\Facade',
        'OpenAI\Contracts\ResponseContract',
        'OpenRouter\Laravel\Testing\OpenRouterFake',
        'OpenAI\Responses\StreamResponse',
    ]);

test('service providers')
    ->expect('OpenRouter\Laravel\ServiceProvider')
    ->toOnlyUse([
        'GuzzleHttp\Client',
        'Illuminate\Support\ServiceProvider',
        'OpenRouter\Laravel',
        'OpenAI',
        'Illuminate\Contracts\Support\DeferrableProvider',

        // helpers...
        'config',
        'config_path',
    ]);
