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

use Closure;

class Renderer
{
    protected array $context = [];

    protected array $env = [];

    protected bool $enabled = true;

    protected bool $debug = false;

    protected string $entry;

    protected string $fallback = '';

    protected null|Closure $entryResolver = null;

    public function __construct(protected Engine $engine) {}

    public function enable(): static
    {
        $this->enabled = true;
        return $this;
    }
    public function enableIf(bool|callable $condition): static
    {
        if (is_callable($condition)) {
            $condition = call_user_func($condition);
        }
        $this->enabled = $condition;
        return $this;
    }

    public function disable(): static
    {
        $this->enabled = false;
        return $this;
    }

    public function debug(bool $debug = true): static
    {
        $this->debug = $debug;

        return $this;
    }

    public function entry(string $entry): static
    {
        $this->entry = $entry;
        return $this;
    }

    public function context(iterable|string $context, mixed $value = null): static
    {
        if (! is_array($context)) {
            $context = [$context => $value];
        }
        foreach ($context as $key => $value) {
            $this->context[$key] = $value;
        }
        return $this;
    }

    public function env(iterable|string $env, mixed $value = null): static
    {
        if (! is_array($env)) {
            $env = [$env => $value];
        }

        foreach ($env as $key => $value) {
            $this->env[$key] = $value;
        }

        return $this;
    }

    public function fallback(string $fallback): static
    {
        $this->fallback = $fallback;
        return $this;
    }

    public function resolveEntryWith(callable $entryResolver): static
    {
        $this->entryResolver = Closure::fromCallable($entryResolver);
        return $this;
    }

    public function render()
    {
        if (! $this->enabled) {
            return $this->fallback;
        }

        try {
            $serverScript = implode(';', [
                $this->dispatchScript(),
                $this->environmentScript(),
                $this->applicationScript(),
            ]);

            $result = $this->engine->run($serverScript);
        } catch (EngineError $exception) {
            if ($this->debug) {
                throw $exception->getException();
            }

            return $this->fallback;
        }

        $decoded = json_decode($result, true);

        if (json_last_error() === JSON_ERROR_NONE) {
            // Looks like the engine returned a JSON object.
            return $decoded;
        }

        // Looks like the engine returned a string.
        return $result;
    }

    protected function environmentScript(): string
    {
        $context = empty($this->context) ? '{}' : json_encode($this->context, JSON_THROW_ON_ERROR);

        $envAssignments = array_map(fn ($value, $key) => "process.env.{$key} = " . json_encode($value, JSON_THROW_ON_ERROR), $this->env, array_keys($this->env));

        return implode(';', [
            '(function () { if (this.process != null) { return; } this.process = { env: {}, argv: [] }; }).call(null)',
            implode(';', $envAssignments),
            "let context = {$context}",
        ]);
    }

    protected function dispatchScript(): string
    {
        return <<<JS
            const dispatch = function (result) {
                return {$this->engine->getDispatchHandler()}(JSON.stringify(result))
            }
            JS;
    }

    protected function applicationScript(): string
    {
        $entry = $this->entryResolver
            ? call_user_func($this->entryResolver, $this->entry)
            : $this->entry;

        if (! file_exists($entry)) {
            throw new ServerScriptDoesNotExist($entry);
        }

        return file_get_contents($entry);
    }
}
