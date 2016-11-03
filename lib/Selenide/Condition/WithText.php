<?php
namespace Selenide;

class Condition_WithText extends Condition_Rule implements Condition_Interface_Match
{

    public function matchElement(\WebDriver_Element $element)
    {
        $actualText = $element->text();
        return (mb_strpos($actualText, $this->expected) !== false);
    }


    protected function assertElement($element)
    {
        return $this->assertCollection([$element], false);
    }


    protected function assertElementNegative($element)
    {
        return $this->assertCollectionNegative([$element], false);
    }


    protected function assertCollection(array $elementListList, $showIndex = true)
    {
        foreach ($elementListList as $index => $e) {
            $actualText = $e->text();
            $prefix = $showIndex ? ('Element[' . $index . ']: ') : '';
            \PHPUnit_Framework_Assert::assertContains(
                $this->expected,
                $actualText,
                $prefix . 'Text not contain ' . $this->expected . ', actual - ' . $actualText
            );
        }
        return $this;
    }


    protected function assertCollectionNegative(array $elementList, $showIndex = true)
    {
        foreach ($elementList as $index => $e) {
            $actualText = $e->text();
            $prefix = $showIndex ? ('Element[' . $index . ']: ') : '';
            \PHPUnit_Framework_Assert::assertNotContains(
                $this->expected,
                $actualText,
                $prefix . 'Text contain ' . $this->expected . ', actual - ' . $actualText
            );
        }
        return $this;
    }
}
