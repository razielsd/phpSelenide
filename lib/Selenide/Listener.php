<?php

namespace Selenide;


abstract class Listener
{
    public function beforeAssert(Condition_Rule $condition, string $locator)
    {
    }


    public function beforeAssertNot(Condition_Rule $condition, string $locator)
    {
    }


    public function beforeSetValue(SelenideElement $element, $value, string $locator, string $textDescription)
    {
        return $value;
    }


    public function beforeClick(SelenideElement $element, string $locator, string $textDescription)
    {
    }


    public function beforeSetDescription(string $textDescription)
    {
    }
}
