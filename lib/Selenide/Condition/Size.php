<?php
namespace Selenide;

class Condition_Size extends Condition_Rule
{

    protected function assertElement($element)
    {
        $collection = is_null($element) ? [] : [$element];
        return $this->assertCollection($collection);
    }


    protected function assertElementNegative($element)
    {
        $collection = is_null($element) ? [] : [$element];
        return $this->assertCollectionNegative($collection);
    }


    protected function assertCollection(array $elementList)
    {
        $actualSize = count($elementList);
        \PHPUnit_Framework_Assert::assertEquals(
            $this->expected,
            $actualSize,
            'Size must be equal ' . $this->expected . ', actual - ' . $actualSize
        );
        return $this;
    }


    protected function assertCollectionNegative(array $elementList)
    {
        $actualSize = count($elementList);
        \PHPUnit_Framework_Assert::assertNotEquals(
            $this->expected,
            $actualSize,
            'Size must be NOT equal ' . $this->expected . ', actual - ' . $actualSize
        );
        return $this;
    }
}
