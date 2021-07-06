<?php

use Alpaca\StoreRouter\Config\Config;

if (PHP_SAPI !== 'cli') {
    (new Config())->load()->apply();
}
