<?php

namespace Alpaca\StoreRouter\Rule;

class Sitemap extends Document
{
    const FILE_PATH_SITEMAP_XML = '*/sitemap.xml';
    const DOCUMENT_CONTENT_TYPE = 'application/xml;charset=utf-8';

    /**
     * @return string
     */
    protected function getRequestPath(): string
    {
        return self::FILE_PATH_SITEMAP_XML;
    }

    /**
     * @return string
     */
    protected function getContentType(): string
    {
        return self::DOCUMENT_CONTENT_TYPE;
    }
}
