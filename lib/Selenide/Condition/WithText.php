<?php
namespace Selenide;

class Condition_WithText extends Condition_Rule implements Condition_Interface_matchCollection
{

    public function matchCollectionPositive($collection)
    {
        $resultList = [];
        /** @var \WebDriver_Element $element */
        foreach ($collection as &$element) {
            $actualText = $element->text();
            if (mb_strpos($actualText, $this->expected) !== false) {
                $resultList[] = $element;
            }
        }
        return $resultList;
    }


    public function matchCollectionNegative($collection)
    {
        $resultList = [];
        /** @var \WebDriver_Element $element */
        foreach ($collection as $element) {
            $actualText = $element->text();
            if (mb_strpos($actualText, $this->expected) === false) {
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


    protected function assertCollection($elementListList, $showIndex = true)
    {
        foreach ($elementListList as $index => $e) {
            $actualText = $e->text();
            $prefix = $showIndex ? ('Element[' . $index . ']: ') : '';
            \PHPUnit_Framework_Assert::assertContains(
                $this->expected,
                $actualText,
                $prefix . 'Text must be contain ' . $this->expected . ', actual - ' . $actualText
            );
        }
        return $this;
    }


    protected function assertCollectionNegative($elementList, $showIndex = true)
    {
        foreach ($elementList as $index => $e) {
            $actualText = $e->text();
            $prefix = $showIndex ? ('Element[' . $index . ']: ') : '';
            \PHPUnit_Framework_Assert::assertNotContains(
                $this->expected,
                $actualText,
                $prefix . 'Text must be NOT contain ' . $this->expected . ', actual - ' . $actualText
            );
        }
        return $this;
    }
}
