<?php

namespace Alpaca\StoreRouter\Rule;

class MageRunCode extends Rule implements RuleContract
{
    const MAGE_RUN_TYPE_DEFAULT = 'store';

    /**
     * @param string $value
     *
     * @return bool
     */
    public function assert(string $value): ?bool
    {
        $_SERVER["MAGE_RUN_TYPE"] = self::MAGE_RUN_TYPE_DEFAULT;

        return (bool)($_SERVER['MAGE_RUN_CODE'] = $value ?: null);
    }
}
