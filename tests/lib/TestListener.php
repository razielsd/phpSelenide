<?php

use Selenide\Condition_Rule;
use Selenide\SelenideElement;


class TestListener extends \Selenide\Listener
{
    public static $data = [];

    public function beforeAssert(Condition_Rule $condition, string $locator)
    {
        static::$data[] = [
            'condition' => $condition,
            'locator' => $locator,
            'method' => 'beforeAssert'
        ];
    }


    public function beforeAssertNot(Condition_Rule $condition, string $locator)
    {
        static::$data[] = [
            'condition' => $condition,
            'locator' => $locator,
            'method' => 'beforeAssertNot'
        ];
    }


    public function beforeSetValue(SelenideElement $element, $value, string $locator, string $textDescription)
    {
        static::$data[] = [
            'element' => $element,
            'value' => $value,
            'locator' => $locator,
            'description' => $textDescription,
            'method' => 'beforeSetValue'
        ];
        return $value;
    }


    public function beforeClick(SelenideElement $element, string $locator, string $textDescription)
    {
        static::$data[] = [
            'element' => $element,
            'locator' => $locator,
            'description' => $textDescription,
            'method' => 'beforeClick'
        ];
    }



    public function beforeSetDescription(string $textDescription)
    {
        static::$data[] = [
            'description' => $textDescription,
            'method' => 'beforeSetDescription'
        ];
    }


    public static function clean()
    {
        static::$data = [];
    }
}
