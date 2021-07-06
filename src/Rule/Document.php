<?php

namespace Alpaca\StoreRouter\Rule;

abstract class Document extends Rule implements RuleContract
{
    /**
     * @return string
     */
    abstract protected function getRequestPath(): string;

    /**
     * @return string
     */
    abstract protected function getContentType(): string;

    /**
     * @param string $value File path.
     *
     * @return bool|null
     */
    public function assert(string $value): ?bool
    {
        if (!fnmatch($this->getRequestPath(), $this->app->requestPath())) {
            return false;
        }

        $filePath = BP . '/' . ltrim($value, '/');

        if (!file_exists($filePath)) {
            return false;
        }

        header('Content-Type: ' . $this->getContentType());
        echo file_get_contents($filePath);
        exit;
    }
}
