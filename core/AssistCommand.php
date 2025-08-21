<?php

namespace Core;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Filesystem\Filesystem;

trait AssistCommand
{
    /**
     * Get the app root path
     *
     * @return string
     */
    public function appPath(): string
    {
        return app()->basePath();
    }

    /**
     * Ensure a directory exists.
     *
     * @param string $path
     * @return void
     * @throws BindingResolutionException
     */
    public function ensureDirectoryExists(string $path): void
    {
        app()->make(Filesystem::class)->ensureDirectoryExists($path);
    }
}