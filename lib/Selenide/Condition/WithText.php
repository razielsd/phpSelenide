<?php
namespace Selenide;

class Condition_WithText extends Condition_Rule
    implements Condition_Interface_Match, Condition_Interface_assertCollection
{

    public function matchElement(\WebDriver_Element $element)
    {
        $actualText = $element->text();
        return (mb_strpos($actualText, $this->expected) !== false);
    }


    public function assertCollectionPositive(array $elementList)
    {
        foreach ($elementList as $index => $e) {
            $actualText = $e->text();
            $prefix = (count($elementList) > 1) ? ('Element[' . $index . ']: ') : '';
            \PHPUnit_Framework_Assert::assertContains(
                $this->expected,
                $actualText,
                $prefix . 'Text not contain ' . $this->expected . ', actual - ' . $actualText
            );
        }
        return $this;
    }


    public function assertCollectionNegative(array $elementList)
    {
        foreach ($elementList as $index => $e) {
            $actualText = $e->text();
            $prefix = (count($elementList) > 1) ? ('Element[' . $index . ']: ') : '';
            \PHPUnit_Framework_Assert::assertNotContains(
                $this->expected,
                $actualText,
                $prefix . 'Text contain ' . $this->expected . ', actual - ' . $actualText
            );
        }
        return $this;
    }
}
