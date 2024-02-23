<?php

uses(\Lame\JSRunner\Tests\RendererTestCase::class);

it('can render an app with an env value', function () {
    $result = $this->renderer
        ->entry(__DIR__.'/scripts/app-with-env-server.js')
        ->env('APP_ENV', 'production')
        ->render();

    expect($result)->toEqual('<p>Hello, world! Rendered in production.</p>');
});

it('can render an app with an env array', function () {
    $result = $this->renderer
        ->entry(__DIR__.'/scripts/app-with-env-server.js')
        ->env(['APP_ENV' => 'production'])
        ->render();

    expect($result)->toEqual('<p>Hello, world! Rendered in production.</p>');
});
