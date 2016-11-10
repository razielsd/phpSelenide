<?php
namespace Selenide;

class Condition_Text extends Condition_Rule
    implements Condition_Interface_Match, Condition_Interface_assertCollection
{
    public function matchElement(\WebDriver_Element $element): bool
    {
        $actualText = $element->text();
        return $this->expected == $actualText;
    }


    public function assertCollectionPositive(array $elementList)
    {
        foreach ($elementList as $index => $e) {
            $actualText = $e->text();
            $prefix = (count($elementList) > 1) ? ('Element[' . $index . ']: ') : '';
            \PHPUnit_Framework_Assert::assertEquals(
                $this->expected,
                $actualText,
                $prefix . 'Not found text: ' . $this->expected . '. Actual: ' . $actualText
            );
        }
        return $this;
    }


    public function assertCollectionNegative(array $elementList)
    {
        foreach ($elementList as $index => $e) {
            $actualText = $e->text();
            $prefix = (count($elementList) > 1) ? ('Element[' . $index . ']: ') : '';
            \PHPUnit_Framework_Assert::assertNotEquals(
                $this->expected,
                $actualText,
                $prefix . 'Found text: ' . $this->expected . '. Actual: ' . $actualText
            );
        }
        return $this;
    }

}
