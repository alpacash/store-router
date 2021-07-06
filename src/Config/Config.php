<?php

namespace Alpaca\StoreRouter\Config;

use Exception;
use Symfony\Component\Yaml\Yaml;

class Config
{
    const CONFIG_FILE_PATH = BP . '/.store-router.app.yaml';
    const ENV_FILE_PATH = BP . '/.store-router.env.yaml';

    /**
     * @var \Alpaca\StoreRouter\Config\Group[]
     */
    protected $groups = [];

    /**
     * @return self
     */
    public function load(): self
    {
        foreach ($this->parsedConfig() as $groupId => $groupConfig) {
            $group = new Group(
                $groupId,
                $groupConfig['conditions'] ?? [],
                $groupConfig['rules'] ?? []
            );

            /** @var \Alpaca\StoreRouter\Config\Group $parent */
            if (!empty($parentId = $groupConfig['inherit'] ?? null)
                && ($parent = $this->groups[$parentId] ?? null) instanceof Group
            ) {
                $group->inherits($parent);
            }

            $this->groups[$groupId] = $group;
        }

        return $this;
    }

    /**
     * Assert rules where conditions comply.
     *
     * @return self
     */
    public function apply(): self
    {
        /** @var \Alpaca\StoreRouter\Config\Group $group */
        foreach ($this->groups as $group) {
            if ($group->conditionCollection()->complies()) {
                $group->ruleCollection()->assert($group->getId());
            }
        }

        return $this;
    }

    /**
     * @return array
     */
    protected function parsedConfig(): array
    {
        try {
            $config = Yaml::parse(@file_get_contents(self::CONFIG_FILE_PATH));

            if (!is_array($config)) {
                return [];
            }
        } catch (Exception $e) {
            return [];
        }

        return $this->mergeWithEnv($config);
    }

    /**
     * @param array $config
     *
     * @return array
     */
    protected function mergeWithEnv(array $config): array
    {
        try {
            $envConfig = Yaml::parse(@file_get_contents(self::ENV_FILE_PATH));

            return is_array($envConfig)
                ? array_replace_recursive($config, $envConfig)
                : $config;
        } catch (Exception $e) {
            return $config;
        }
    }
}
