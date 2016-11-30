<?php
namespace Selenide;

class Condition_Child extends Condition_Rule
    implements Condition_Interface_Match, Condition_Interface_assertCollection
{
    public function matchElement(\WebDriver_Element $element): bool
    {
        return $this->getActualValue($element);
    }


    public function assertCollectionPositive(array $elementList)
    {
        if (empty($elementList)) {
            throw new Exception_ElementNotFound('Elements not found for assertion');
        }
        foreach ($elementList as $index => $e) {
            $prefix = (count($elementList) > 1) ? ('Element[' . $index . ']: ') : '';
            \PHPUnit_Framework_Assert::assertTrue(
                $this->getActualValue($e),
                $prefix . 'Not found child element: ' . $this->expected->asString()
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
            $prefix = (count($elementList) > 1) ? ('Element[' . $index . ']: ') : '';
            \PHPUnit_Framework_Assert::assertFalse(
                $this->getActualValue($e),
                $prefix . 'Found child element: ' . $this->expected->asString()
            );
        }
        return $this;
    }


    protected function getActualValue(\WebDriver_Element $element)
    {
        return $element->child($this->expected->asString())->isPresent();
    }

}
