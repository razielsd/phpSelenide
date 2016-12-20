<?php
namespace Selenide;

class Condition_Enabled extends Condition_Rule
    implements Condition_Interface_Match, Condition_Interface_assertCollection
{
    public function matchElement(\WebDriver_Element $element): bool
    {
        return $element->enabled();
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
                $element->enabled(),
                $prefix . 'Element is not enabled'
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
                $element->enabled(),
                $prefix . 'Element is enabled'
            );
        }
        return $this;
    }

}
