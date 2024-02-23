<?php

use Lame\JSRunner\Engines\V8;
use Lame\JSRunner\EngineError;
use Lame\JSRunner\Factory;
use Lame\JSRunner\V8Engine;

beforeEach(function () {
    if (!extension_loaded('v8js')) {
        $this->markTestSkipped('The V8Js extension is not available.');
    }
});


it('can run a script and return its contents', function () {
    $engine = Factory::V8();
    $result = $engine->run("print('Hello, world!')");
    expect($result)->toEqual('Hello, world!');
});

it('throws an engine error when a script is invalid', function () {
    $engine = Factory::V8();
    $this->expectException(EngineError::class);
    $engine->run('foo.bar.baz()');
});

it('has a dispatch handler', function () {
    $engine = Factory::V8();
    expect($engine->getDispatchHandler())->toEqual('print');
});
