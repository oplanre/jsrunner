<?php

namespace Lame\JSRunner\Tests;

use Lame\JSRunner\Factory;
use PHPUnit\Framework\TestCase as BaseTestCase;
use Lame\JSRunner\NodeEngine;
use Lame\JSRunner\Renderer;

abstract class RendererTestCase extends BaseTestCase
{
    /** @var  */
    protected Renderer $renderer;

    protected function setup(): void
    {

        $this->renderer = (Factory::Renderer(Factory::Node(__DIR__ . '/temp', getenv('NODE_PATH') ?: '/usr/local/bin/node')))->debug();
    }
}
