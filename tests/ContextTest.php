<?php

uses(\Lame\JSRunner\Tests\RendererTestCase::class);

it('can render an app with a context value', function () {
    $result = $this->renderer
        ->entry(__DIR__.'/scripts/app-with-context-server.js')
        ->context('user', ['name' => 'Lanre'])
        ->render();
    var_dump($result);
    expect($result)->toEqual('<p>Hello, Lanre!</p>');
});

it('can render an app with a context array', function () {
    $result = $this->renderer
        ->entry(__DIR__.'/scripts/app-with-context-server.js')
        ->context(['user' => ['name' => 'Lanre']])
        ->render();

    expect($result)->toEqual('<p>Hello, Lanre!</p>');
});
