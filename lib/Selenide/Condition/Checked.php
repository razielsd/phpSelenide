<?php
namespace Selenide;

class Condition_Checked extends Condition_Rule
    implements Condition_Interface_Match, Condition_Interface_assertCollection
{
    public function matchElement(\WebDriver_Element $element): bool
    {
        return $element->checked();
    }


    public function assertCollectionPositive(array $elementList)
    {
        if (empty($elementList)) {
            throw new Exception_ElementNotFound('Elements not found for assertion');
        }
        /** @var \WebDriver_Element $element */
        foreach ($elementList as $index => $element) {
            $prefix = (count($elementList) > 1) ? ('Element[' . $index . ']: ') : '';
            \PHPUnit_Framework_Assert::assertTrue(
                $element->checked(),
                $prefix . 'Element is not checked'
            );
        }
        return $this;
    }


    public function assertCollectionNegative(array $elementList)
    {
        if (empty($elementList)) {
            throw new Exception_ElementNotFound('Elements not found for assertion');
        }
        /** @var \WebDriver_Element $element */
        foreach ($elementList as $index => $element) {
            $prefix = (count($elementList) > 1) ? ('Element[' . $index . ']: ') : '';
            \PHPUnit_Framework_Assert::assertFalse(
                $element->checked(),
                $prefix . 'Element is checked'
            );
        }
        return $this;
    }

}
