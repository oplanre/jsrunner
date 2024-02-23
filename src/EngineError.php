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

use Exception;
use RuntimeException;

class EngineError extends RuntimeException
{
    public function __construct(protected Exception $originalException) {}

    public function getException(): Exception
    {
        return $this->originalException ?? $this;
    }
}
