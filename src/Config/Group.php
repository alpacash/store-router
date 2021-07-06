<?php

namespace Alpaca\StoreRouter\Config;

class Group
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var \Alpaca\StoreRouter\Config\ConditionCollection[]
     */
    protected $conditions;

    /**
     * @var \Alpaca\StoreRouter\Config\Rule[]
     */
    protected $rules;

    /**
     * @param string $id DNS compliant string.
     * @param array  $conditions
     * @param array  $rules
     */
    public function __construct(
        string $id,
        array $conditions = [],
        array $rules = []
    ) {
        $this->id = $id;
        $this->conditions = $this->createConditions($conditions);
        $this->rules = $this->createRules($rules);
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return \Alpaca\StoreRouter\Config\ConditionCollection
     */
    public function conditionCollection(): ConditionCollection
    {
        return $this->conditions;
    }

    /**
     * @return \Alpaca\StoreRouter\Config\RuleCollection
     */
    public function ruleCollection(): RuleCollection
    {
        return $this->rules;
    }

    /**
     * Inherit another group's data.
     *
     * @param \Alpaca\StoreRouter\Config\Group $group
     *
     * @return \Alpaca\StoreRouter\Config\Group
     */
    public function inherits(Group $group)
    {
        $this->conditions = $this->conditions->merge($group->conditionCollection());
        $this->rules = $this->rules->merge($group->ruleCollection());

        return $this;
    }

    /**
     * @param array $conditions
     *
     * @return \Alpaca\StoreRouter\Config\ConditionCollection
     */
    protected function createConditions(array $conditions): ConditionCollection
    {
        return new ConditionCollection(
            $conditions['hosts'] ?? []
        );
    }

    /**
     * @param array $rules
     *
     * @return \Alpaca\StoreRouter\Config\RuleCollection
     */
    protected function createRules(array $rules): RuleCollection
    {
        return new RuleCollection($rules);
    }
}
