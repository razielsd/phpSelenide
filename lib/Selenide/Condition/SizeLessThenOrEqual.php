<?php

namespace Selenide;

use PHPUnit\Framework\Assert;

class Condition_SizeLessThenOrEqual extends Condition_Rule
    implements Condition_Interface_assertCollection, Condition_Interface_ExpectedCollection
{


    public function matchCollection(array $collection): bool
    {
        return $this->expected >= count($collection);
    }


    public function assertCollectionPositive(array $elementList)
    {
        $actualSize = count($elementList);
        Assert::assertLessThanOrEqual(
            $this->expected,
            $actualSize,
            'Size must be less then or equal ' . $this->expected . ', actual - ' . $actualSize
        );
        return $this;
    }


    public function assertCollectionNegative(array $elementList)
    {
        $actualSize = count($elementList);
        Assert::assertGreaterThan(
            $this->expected,
            $actualSize,
            'Size must be greater then ' . $this->expected . ', actual - ' . $actualSize
        );
        return $this;
    }
}
