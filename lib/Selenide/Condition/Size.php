<?php

namespace Selenide;

use PHPUnit\Framework\Assert;

class Condition_Size extends Condition_Rule
    implements Condition_Interface_assertCollection, Condition_Interface_ExpectedCollection
{


    public function matchCollection(array $collection): bool
    {
        return $this->expected == count($collection);
    }


    public function assertCollectionPositive(array $elementList)
    {
        $actualSize = count($elementList);
        Assert::assertEquals(
            $this->expected,
            $actualSize,
            'Size must be equal ' . $this->expected . ', actual - ' . $actualSize
        );
        return $this;
    }


    public function assertCollectionNegative(array $elementList)
    {
        $actualSize = count($elementList);
        Assert::assertNotEquals(
            $this->expected,
            $actualSize,
            'Size must be NOT equal ' . $this->expected . ', actual - ' . $actualSize
        );
        return $this;
    }
}
