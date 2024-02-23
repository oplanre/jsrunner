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
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

trait BinaryEngineImplementer
{
    public function __construct(
        protected string $tempPath,
        protected string $binPath = ''
    ) {
        if (! $this->binPath) {
            $this->binPath = $this->find();
        }
    }

    public function __destruct()
    {
        // clean up temp files
        $this->cleanUpTempFiles();
    }

    public static function create(string $tempPath, string $binPath = ''): static
    {
        return new static($tempPath, $binPath);
    }

    /**
     * @throws Exception
     */
    public function run(string $script): string
    {
        $tempFilePath = $this->createTempFilePath();
        file_put_contents($tempFilePath, $script);
        $command = "{$this->binPath} {$tempFilePath}";
        $process = Process::fromShellCommandline($command);
        try {
            return substr($process->mustRun()->getOutput(), 0, -1);
        } catch (ProcessFailedException $exception) {
            throw new EngineError($exception);
        } finally {
            unlink($tempFilePath);
        }
        return '';
    }

    public function getDispatchHandler(): string
    {
        return 'console.log';
    }

    /**
     * @throws Exception
     */
    public function createTempFilePath(): string
    {
        return implode(
            DIRECTORY_SEPARATOR,
            [
                $this->tempPath,
                md5(intval(microtime(true) * 1000) . random_bytes(5))
                . '.js',
            ]
        );
    }

    public function cleanUpTempFiles(): void
    {
        $files = glob($this->tempPath . DIRECTORY_SEPARATOR . '*');
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
    }

    public function isInstalled(): bool
    {
        $process = new Process(['which', static::BINARY]);
        $process->run();
        return $process->isSuccessful();
    }

    public static function findBinary(string $binary): string
    {
        $process = new Process(['which', $binary]);
        $process->run();
        if (! $process->isSuccessful()) {
            throw new RuntimeException("Could not find binary: {$binary}");
        }
        return trim($process->getOutput());
    }

    public function find(): string
    {
        return self::findBinary(static::BINARY);
    }
}
