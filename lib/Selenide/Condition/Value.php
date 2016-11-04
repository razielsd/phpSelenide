<?php
namespace Selenide;

class Condition_Value extends Condition_Rule
    implements Condition_Interface_Match, Condition_Interface_assertCollection
{

    public function matchElement(\WebDriver_Element $element): bool
    {
        return $this->getActualValue($element) == $this->expected;
    }



    public function assertCollectionPositive(array $elementList)
    {
        foreach ($elementList as $index => $e) {
            $actual = $this->getActualValue($e);
            \PHPUnit_Framework_Assert::assertEquals(
                $this->expected,
                $actual,
                'Value must be equal ' . $this->expected . ', actual - ' . $actual
            );
        }
        return $this;
    }


    public function assertCollectionNegative(array $elementList)
    {
        foreach ($elementList as $index => $e) {
            $actual = $this->getActualValue($e);
            \PHPUnit_Framework_Assert::assertNotEquals(
                $this->expected,
                $actual,
                'Value must be equal ' . $this->expected . ', actual - ' . $actual
            );
        }
        return $this;
    }


    public function getActualValue(\WebDriver_Element $element)
    {
        return $element->value();
    }
}