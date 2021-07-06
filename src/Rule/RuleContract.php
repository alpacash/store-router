<?php

namespace Alpaca\StoreRouter\Rule;

interface RuleContract
{
    /**
     * @param string $value
     *
     * @return bool
     */
    public function assert(string $value): ?bool;
}
