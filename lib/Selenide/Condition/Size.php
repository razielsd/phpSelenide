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


    protected function assertCollection($elementList)
    {
        $actualSize = count($elementList);
        if ($actualSize <> $this->expected) {
            throw new Assertion('Size must be equal ' . $this->expected . ', actual - ' . $actualSize);
        }
        return $this;
    }


    protected function assertCollectionNegative($elementList)
    {
        $actualSize = count($elementList);
        if ($actualSize == $this->expected) {
            throw new Assertion('Size must be NOT equal ' . $this->expected . ', actual - ' . $actualSize);
        }
        return $this;
    }
}
