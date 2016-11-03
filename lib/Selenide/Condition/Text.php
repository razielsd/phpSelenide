<?php
namespace Selenide;

class Condition_Text extends Condition_Rule implements Condition_Interface_Match
{
    public function matchElement(\WebDriver_Element $element)
    {
        $actualText = $element->text();
        return $this->expected == $actualText;
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
            \PHPUnit_Framework_Assert::assertEquals(
                $this->expected,
                $actualText,
                $prefix . 'Not found text: ' . $this->expected . '. Actual: ' . $actualText
            );
        }
        return $this;
    }


    protected function assertCollectionNegative(array $elementList, $showIndex = true)
    {
        foreach ($elementList as $index => $e) {
            $actualText = $e->text();
            $prefix = $showIndex ? ('Element[' . $index . ']: ') : '';
            \PHPUnit_Framework_Assert::assertNotEquals(
                $this->expected,
                $actualText,
                $prefix . 'Found text: ' . $this->expected . '. Actual: ' . $actualText
            );
            if ($this->expected == $actualText) {

                throw new Assertion(

                );
            }
        }
        return $this;
    }

}
