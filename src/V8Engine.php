<?php

declare(strict_types=1);
/**
 * This file is part of JSRunner by Lanre.
 *
 * @link     https://github.com/oplanre/jsrunner
 * @document https://github.com/oplanre/jsrunner/blob/master/README.md
 * @license  https://github.com/oplanre/jsrunner/blob/master/LICENSE
 */

namespace Lame\JSRunner;

use V8Js;
use V8JsMemoryLimitException;
use V8JsTimeLimitException;

if (extension_loaded('v8js')) {
    class V8Engine implements Engine
    {
        public function __construct(protected V8Js $engine = new V8Js()) {}

        public function run(string $script): string
        {
            try {
                ob_start();

                $this->engine->executeString($script);

                return ob_get_contents();
            } catch (V8JsMemoryLimitException|V8JsTimeLimitException $exception) {
                throw new EngineError($exception);
            } finally {
                ob_end_clean();
            }
        }

        public function getDispatchHandler(): string
        {
            return 'print';
        }

        public function isInstalled(): bool
        {
            return true;
        }
    }
} else {
    class V8Engine implements Engine
    {
        public function __construct(mixed $_ = null) {}

        public function run(string $_): string
        {
            return 'V8Js is not installed. Please install it to use this engine.';
        }

        public function getDispatchHandler(): string
        {
            return 'print';
        }

        public function isInstalled(): bool
        {
            return false;
        }
    }
}
