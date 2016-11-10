<?php
namespace Selenide;

class Condition_Value extends Condition_Rule
    implements Condition_Interface_Match, Condition_Interface_assertCollection
{

    public function matchElement(\WebDriver_Element $element): bool
    {
        var_dump($this->getActualValue($element) . ' == ' . $this->expected);
        return $this->getActualValue($element) == $this->expected;
    }



    public function assertCollectionPositive(array $elementList)
    {
        if (empty($elementList)) {
            var_dump(__METHOD__ . '#' . '->JOPA');
            throw new Exception_ElementNotFound('Elements not found for assertion');
        }
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
        if (empty($elementList)) {
            throw new Exception_ElementNotFound('Elements not found for assertion');
        }
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
        var_dump(__METHOD__);
        $r =  $element->value();
        var_dump(__METHOD__ . '#2');
        return $r;
    }
}