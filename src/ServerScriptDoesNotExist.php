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

use RuntimeException;

class ServerScriptDoesNotExist extends RuntimeException
{
    public function __construct(string $path)
    {
        parent::__construct("Server script at path `{$path}` doesn't exist");
    }
}
