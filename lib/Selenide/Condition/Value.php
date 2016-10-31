<?php
namespace Selenide;

class Condition_Value extends Condition_Rule
{
    protected function assert($element)
    {
        $actual = $this->getActualValue($element);
        if ($actual <> $this->expected) {
            throw new Assertion('Value must be equal ' . $this->expected . ', actual - ' . $actual);
        }
    }


    protected function assertNegative($element)
    {
        $actual = $this->getActualValue($element);
        if ($actual == $this->expected) {
            throw new Assertion('Value must be NOT equal ' . $this->expected . ', actual - ' . $actual);
        }
    }


    protected function assertCollection($elementList)
    {
        foreach ($elementList as $index => $e) {
            $actual = $this->getActualValue($e);
            if ($this->expected != $actual) {
                throw new Assertion(
                    'Value must be equal ' . $this->expected . ', actual - ' . $actual
                );
            }
        }
        return $this;
    }


    protected function assertCollectionNegative($elementList)
    {
        foreach ($elementList as $index => $e) {
            $actual = $this->getActualValue($e);
            if ($this->expected != $actual) {
                throw new Assertion(
                    'Value must be NOT equal ' . $this->expected . ', actual - ' . $actual
                );
            }
        }
        return $this;
    }


    public function getActualValue(\WebDriver_Element $element)
    {
        return $element->attribute('value');
    }
}