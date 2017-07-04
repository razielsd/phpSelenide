<?php
namespace Selenide;

/**
 * @property bool $isPositive
 * @property By $locator
 * @property int $type
 * @property Condition_Rule $condition
 */
abstract class Selector
{
    const TYPE_LOCATOR = 2;
    /**
     * Filter element(s) by condition
     */
    const TYPE_CONDITION = 3;
    const TYPE_WAIT = 4;



    abstract public function asString(): string;


}
