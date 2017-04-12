<?php

namespace Selenide;

use PHPUnit\Framework\Assert;

class Condition_Exists extends Condition_Rule
    implements Condition_Interface_assertCollection, Condition_Interface_ExpectedCollection
{


    public function matchCollection(array $collection): bool
    {
        return count($collection) > 0;
    }


    public function assertCollectionPositive(array $elementList)
    {
        $actualSize = count($elementList);
        Assert::assertTrue(
            $actualSize > 0,
            'Element must be exists'
        );
        return $this;
    }


    public function assertCollectionNegative(array $elementList)
    {
        $actualSize = count($elementList);
        Assert::assertTrue(
            $actualSize < 1,
            'Element must be NOT exists'
        );
        return $this;
    }
}
