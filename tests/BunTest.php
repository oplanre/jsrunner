<?php

use Lame\JSRunner\Factory;
use Lame\JSRunner\NodeEngine;
use Lame\JSRunner\EngineError;

beforeEach(function () {
    $this->bunPath = getenv('BUN_PATH') ?: '';
    $this->tempPath = __DIR__ . '/temp';
});
it('can run a script and return its contents', function () {
    $engine = Factory::Bun($this->tempPath, $this->bunPath);

    $result = $engine->run("console.log('Hello, world!')");

    expect($result)->toEqual('Hello, world!');
});

it('throws an engine error when a script is invalid', function () {
    $engine = Factory::Bun($this->tempPath, $this->bunPath);

    $this->expectException(EngineError::class);

    $engine->run('foo.bar.baz()');
});

it('has a dispatch handler', function () {
    $engine = Factory::Bun($this->tempPath, $this->bunPath);

    expect($engine->getDispatchHandler())->toEqual('console.log');
});
