<?php
namespace Selenide;

class Condition_Visible extends Condition_Rule implements Condition_Interface_Match
{
    public function matchElement(\WebDriver_Element $element)
    {
        return $element->isDisplayed();
    }


    public function matchCollectionNegative($collection)
    {
        $resultList = [];
        /** @var \WebDriver_Element $element */
        foreach ($collection as $element) {
            if (!$element->isDisplayed()) {
                $resultList[] = $element;
            }
        }
        return $resultList;
    }


    protected function assertElement($element)
    {
        return $this->assertCollection([$element], false);
    }


    protected function assertElementNegative($element)
    {
        return $this->assertCollectionNegative([$element], false);
    }


    protected function assertCollection(array $elementList, $showIndex = true)
    {
        /** @var \WebDriver_Element $element */
        foreach ($elementList as $index => $element) {
            $prefix = $showIndex ? ('Element[' . $index . ']: ') : '';
            \PHPUnit_Framework_Assert::assertTrue(
                $element->isDisplayed(),
                $prefix . 'Element is not visible'
            );
        }
        return $this;
    }


    protected function assertCollectionNegative(array $elementList, $showIndex = true)
    {
        /** @var \WebDriver_Element $element */
        foreach ($elementList as $index => $element) {
            $prefix = $showIndex ? ('Element[' . $index . ']: ') : '';
            \PHPUnit_Framework_Assert::assertFalse(
                $element->isDisplayed(),
                $prefix . 'Element is visible'
            );
        }
        return $this;
    }

}
