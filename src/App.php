<?php

namespace Alpaca\StoreRouter;

class App
{
    const DEFAULT_HOST = 'default';
    const DEFAULT_PATH = '/';

    /**
     * @return self
     */
    public static function instance()
    {
        return new static();
    }

    /**
     * Get http host.
     *
     * @return string
     */
    public function httpHost(): string
    {
        return $_SERVER['HTTP_HOST'] ?? self::DEFAULT_HOST;
    }

    /**
     * Get http host.
     *
     * @return string
     */
    public function remoteAddress(): ?string
    {
        return $_SERVER['REMOTE_ADDR'] ?? null;
    }

    /**
     * Get request path.
     *
     * @return string
     */
    public function requestPath(): string
    {
        $requestPath = strtok($_SERVER['REQUEST_URI'] ?? self::DEFAULT_PATH, '?');

        return rtrim($requestPath, '/') . '/';
    }
}
