<?php
namespace Selenide;

class Condition_Visible extends Condition_Rule
    implements Condition_Interface_Match, Condition_Interface_assertCollection
{
    public function matchElement(\WebDriver_Element $element)
    {
        return $element->isDisplayed();
    }


    public function assertCollectionPositive(array $elementList)
    {
        /** @var \WebDriver_Element $element */
        foreach ($elementList as $index => $element) {
            $prefix = (count($elementList) > 1) ? ('Element[' . $index . ']: ') : '';
            \PHPUnit_Framework_Assert::assertTrue(
                $element->isDisplayed(),
                $prefix . 'Element is not visible'
            );
        }
        return $this;
    }


    public function assertCollectionNegative(array $elementList)
    {
        /** @var \WebDriver_Element $element */
        foreach ($elementList as $index => $element) {
            $prefix = (count($elementList) > 1) ? ('Element[' . $index . ']: ') : '';
            \PHPUnit_Framework_Assert::assertFalse(
                $element->isDisplayed(),
                $prefix . 'Element is visible'
            );
        }
        return $this;
    }

}
