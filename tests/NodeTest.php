<?php

use Lame\JSRunner\Factory;
use Lame\JSRunner\NodeEngine;
use Lame\JSRunner\EngineError;

beforeEach(function () {
    $this->nodePath = getenv('NODE_PATH') ?: '/usr/local/bin/node';
    $this->tempPath = __DIR__ . '/temp';
});
it('can run a script and return its contents', function () {
    $engine = Factory::Node($this->tempPath, $this->nodePath);

    $result = $engine->run("console.log('Hello, world!')");

    expect($result)->toEqual('Hello, world!');
});

it('throws an engine error when a script is invalid', function () {
    $engine = Factory::Node($this->tempPath, $this->nodePath);

    $this->expectException(EngineError::class);

    $engine->run('foo.bar.baz()');
});

it('has a dispatch handler', function () {
    $engine = Factory::Node($this->tempPath, $this->nodePath);

    expect($engine->getDispatchHandler())->toEqual('console.log');
});
