<?php

namespace Alpaca\StoreRouter\Config;

use Alpaca\StoreRouter\App;

class ConditionCollection
{
    const DEFAULT_HOST = 'default';

    /**
     * @var array
     */
    protected $hosts;

    /**
     * @var \Alpaca\StoreRouter\App
     */
    protected $app;

    /**
     * @param array $hosts
     */
    public function __construct(
        array $hosts = []
    ) {
        $this->hosts = $hosts;
        $this->app = App::instance();
    }

    /**
     * @param \Alpaca\StoreRouter\Config\ConditionCollection $conditions
     *
     * @return self
     */
    public function merge(ConditionCollection $conditions): self
    {
        $this->hosts = array_merge_recursive($this->hosts, $conditions->getHosts());

        return $this;
    }

    /**
     * Multi-dimensional array in the following format:
     * ['example.com'] => [['/path1'], ['/path2']]
     *
     * @return array
     */
    public function getHosts(): array
    {
        return $this->hosts;
    }

    /**
     * @return bool
     */
    public function complies(): bool
    {
        $hostPaths = $this->extractHostPaths();

        if (empty($hostPaths)) {
            return false;
        }

        foreach ($hostPaths as $path) {
            if (fnmatch($path, $this->app->requestPath())) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return array
     */
    protected function extractHostPaths(): array
    {
        foreach ($this->hosts as $host => $paths) {
            if (fnmatch($host, $this->app->httpHost())) {
                return !is_array($paths) ? [$paths] : $paths;
            }
        }

        return $this->hosts[self::DEFAULT_HOST] ?? [];
    }
}
