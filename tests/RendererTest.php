<?php

use Lame\JSRunner\ServerScriptDoesNotExist;
use Symfony\Component\Process\Exception\ProcessFailedException;

uses(\Lame\JSRunner\Tests\RendererTestCase::class);

it('can render a javascript app', function () {
    $result = $this->renderer
        ->entry(__DIR__ . '/scripts/app-server.js')
        ->render();

    expect($result)->toEqual('<p>Hello, world!</p>');
});

it('can decode json', function () {
    $result = $this->renderer
        ->entry(__DIR__ . '/scripts/app-with-json-server.js')
        ->render();

    expect($result)->toEqual(['foo' => 'bar']);
});

it('renders a fallback when disabled', function () {
    $result = $this->renderer
        ->entry(__DIR__ . '/scripts/app-server.js')
        ->fallback('<div id="app"></div>')
        ->disable()
        ->render();

    expect($result)->toEqual('<div id="app"></div>');
});

it('renders a fallback when server rendering fails and debug is disabled', function () {
    $result = $this->renderer
        ->entry(__DIR__ . '/scripts/app-broken-server.js')
        ->fallback('<div id="app"></div>')
        ->debug(false)
        ->render();

    expect($result)->toEqual('<div id="app"></div>');
});

it('throws an engine error when server rendering fails and debug is enabled', function () {
    $this->expectException(ProcessFailedException::class);

    $this->renderer
        ->entry(__DIR__ . '/scripts/app-broken-server.js')
        ->debug()
        ->render();
});

it('always throws an exception when the server script does not exist', function () {
    $this->expectException(ServerScriptDoesNotExist::class);

    $this->renderer
        ->entry(__DIR__ . '/scripts/app-doesnt-exist.js')
        ->debug(false)
        ->render();
});

it('can register an entry resolver', function () {
    $result = $this->renderer
        ->resolveEntryWith(function (string $identifier) {
            return __DIR__ . "/scripts/{$identifier}-server.js";
        })
        ->entry('app')
        ->render();

    expect($result)->toEqual('<p>Hello, world!</p>');
});
