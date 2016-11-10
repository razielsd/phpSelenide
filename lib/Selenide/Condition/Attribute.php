<?php
namespace Selenide;

class Condition_Attribute extends Condition_Rule
    implements Condition_Interface_Match, Condition_Interface_assertCollection
{
    protected $attrName = '';

    public function __construct($attrName, $expected)
    {
        $this->attrName = $attrName;
        parent::__construct($expected);
    }


    public function matchElement(\WebDriver_Element $element): bool
    {
        $actualValue = $this->getActualValue($element);
        return $this->expected == $actualValue;
    }


    public function assertCollectionPositive(array $elementList)
    {
        if (empty($elementList)) {
            throw new Exception_ElementNotFound('Elements not found for assertion');
        }
        foreach ($elementList as $index => $e) {
            $actualValue = $this->getActualValue($e);
            $prefix = (count($elementList) > 1) ? ('Element[' . $index . ']: ') : '';
            \PHPUnit_Framework_Assert::assertEquals(
                $this->expected,
                $actualValue,
                $prefix . 'Not found attribute: ' . $this->attrName . ' with value ' .
                    $this->expected . '. Actual: ' . $actualValue
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
            $actualValue = $this->getActualValue($e);
            $prefix = (count($elementList) > 1) ? ('Element[' . $index . ']: ') : '';
            \PHPUnit_Framework_Assert::assertNotEquals(
                $this->expected,
                $actualValue,
                $prefix . 'Found attribute: ' . $this->attrName . ' with value ' .
                $this->expected . '. Actual: ' . $actualValue
            );

        }
        return $this;
    }


    protected function getActualValue(\WebDriver_Element $element)
    {
        return $element->attribute($this->attrName);;
    }

}
