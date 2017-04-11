<?php

namespace Selenide;

class ListenerCollection
{
    protected $collection = [];


    public function addListener(Listener $listener)
    {
        $this->collection[] = $listener;
    }


    public function beforeAssert(Condition_Rule $condition, string $locator)
    {
        /* @var Listener $listener */
        foreach ($this->collection as $listener) {
            $listener->beforeAssert($condition, $locator);
        }
    }


    public function beforeAssertNot(Condition_Rule $condition, string $locator)
    {
        /* @var Listener $listener */
        foreach ($this->collection as $listener) {
            $listener->beforeAssertNot($condition, $locator);
        }
    }


    public function beforeSetValue(SelenideElement $element, $value, string $locator, string $textDescription)
    {
        /* @var Listener $listener */
        foreach ($this->collection as $listener) {
            $value = $listener->beforeSetValue($element, $value, $locator, $textDescription);
        }
        return $value;
    }


    public function beforeClick(SelenideElement $element, string $locator, string $textDescription)
    {
        /* @var Listener $listener */
        foreach ($this->collection as $listener) {
            $listener->beforeClick($element, $locator, $textDescription);
        }
    }


    public function beforeSetDescription(string $textDescription)
    {
        /* @var Listener $listener */
        foreach ($this->collection as $listener) {
            $listener->beforeSetDescription($textDescription);
        }
    }
}
