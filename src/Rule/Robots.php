<?php

namespace Alpaca\StoreRouter\Rule;

class Robots extends Document
{
    const FILE_PATH_ROBOTS_TXT = '*/robots.txt';
    const DOCUMENT_CONTENT_TYPE = 'text/plain;charset=utf-8';

    /**
     * @return string
     */
    protected function getRequestPath(): string
    {
        return self::FILE_PATH_ROBOTS_TXT;
    }

    /**
     * @return string
     */
    protected function getContentType(): string
    {
        return self::DOCUMENT_CONTENT_TYPE;
    }
}
