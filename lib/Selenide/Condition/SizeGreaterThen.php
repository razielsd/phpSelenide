<?php
namespace Selenide;

class Condition_SizeGreaterThen extends Condition_Rule
{

    protected function assert($element)
    {
        $actualSize = count($element);
        \PHPUnit_Framework_Assert::assertGreaterThan(
            $this->expected,
            $actualSize,
            'Size must be greater then ' . $this->expected . ', actual - ' . $actualSize
        );
    }


    protected function assertNegative($element)
    {
        $actualSize = count($element);
        \PHPUnit_Framework_Assert::assertLessThanOrEqual(
            $this->expected,
            $actualSize,
            'Size must be less then or equal ' . $this->expected . ', actual - ' . $actualSize
        );
    }
}
