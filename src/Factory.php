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

final class Factory
{
    public static function Renderer(Engine $engine): Renderer
    {
        return new Renderer($engine);
    }

    public static function Node(string $tempPath, string $binPath = ''): NodeEngine
    {
        return new NodeEngine($tempPath, $binPath);
    }

    public static function Bun(string $tempPath, string $binPath = ''): BunEngine
    {
        return new BunEngine($tempPath, $binPath);
    }

    public static function V8(): V8Engine
    {
        return new V8Engine();
    }

    public static function Create(string $engine, string $tempPath, string $binPath = ''): Engine
    {
        if ($engine === 'node') {
            return self::Node($tempPath, $binPath);
        }
        if ($engine === 'bun') {
            return self::Bun($tempPath, $binPath);
        }
        if ($engine === 'v8' && extension_loaded('v8js')) {
            return self::V8();
        }
        throw new EngineError(new Exception("Engine {$engine} is not supported."));
    }

    public static function Guess(string $tempPath = ''): Engine
    {
        if (extension_loaded('v8js')) {
            return self::V8();
        }
        $tempPath = $tempPath ?: __DIR__ . '/jsrunner_temp';

        foreach (['node', 'bun'] as $tag) {
            [$installed, $engine] = self::guess_helper($tag, $tempPath);
            if ($installed) {
                return $engine;
            }
        }
        throw new EngineError(new Exception('No supported engine is installed.'));
    }

    private static function guess_helper(string $tag, string $tempPath): array
    {
        $engine = self::Create($tag, $tempPath);
        return [$engine->isInstalled(), $engine];
    }
}
