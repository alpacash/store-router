<?php

namespace Alpaca\StoreRouter\Rule;

use Alpaca\StoreRouter\App;

abstract class Rule
{
    /**
     * @var string
     */
    private $groupId = null;

    /**
     * @var \Alpaca\StoreRouter\App
     */
    protected $app;

    /**
     * @return void
     */
    public function __construct()
    {
        $this->app = new App();
    }

    /**
     * @param string $groupId
     *
     * @return self
     */
    public function setGroupId(string $groupId): self
    {
        $this->groupId = $groupId;

        return $this;
    }

    /**
     * @return string
     */
    public function getGroupId(): ?string
    {
        return $this->groupId;
    }
}
