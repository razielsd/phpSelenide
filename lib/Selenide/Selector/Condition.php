<?php

namespace Selenide;


class Selector_Condition extends Selector
{
    protected $condition = null;
    protected $isPositive = true;


    public function __construct(Condition_Rule $condition, bool $isPositive = true)
    {
        $this->condition = $condition;
        $this->isPositive = $isPositive;
        $this->type = self::TYPE_CONDITION;
    }


    public function asString(): string
    {
        $locator = $this->condition->getLocator();
        if (!$this->isPositive) {
            $locator = 'Not(' . $locator . ')';
        }
        return $locator;
    }


    public function getCondition(): Condition_Rule
    {
        return $this->condition;
    }


    public function isPositive(): bool
    {
        return $this->isPositive;
    }

}
