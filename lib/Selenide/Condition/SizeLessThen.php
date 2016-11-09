<?php
namespace Selenide;

class Condition_SizeLessThen extends Condition_Rule
    implements Condition_Interface_assertCollection, Condition_Interface_ExpectedCollection
{
    public function matchCollection(array $collection): bool
    {
        return $this->expected > count($collection);
    }


    public function assertCollectionPositive(array $elementList)
    {
        $actualSize = count($elementList);
        \PHPUnit_Framework_Assert::assertLessThan(
            $this->expected,
            $actualSize,
            'Size must be less then ' . $this->expected . ', actual - ' . $actualSize
        );
        return $this;
    }


    public function assertCollectionNegative(array $elementList)
    {
        $actualSize = count($elementList);
        \PHPUnit_Framework_Assert::assertGreaterThanOrEqual(
            $this->expected,
            $actualSize,
            'Size must be greater then or equal ' . $this->expected . ', actual - ' . $actualSize
        );
        return $this;
    }
}

