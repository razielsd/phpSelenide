<?php
namespace Selenide;

class Condition_Text extends Condition_Rule implements Condition_Interface_matchCollection
{
    public function matchCollectionPositive($collection)
    {
        $resultList = [];
        /** @var \WebDriver_Element $element */
        foreach ($collection as &$element) {
            $actualText = $element->text();
            if ($this->expected == $actualText) {
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
            if ($this->expected != $actualText) {
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
            if ($this->expected != $actualText) {
                $prefix = $showIndex ? ('Element[' . $index . ']: ') : '';
                throw new Assertion(
                    $prefix . 'Text must be equal ' . $this->expected . ', actual - ' . $actualText
                );
            }
        }
        return $this;
    }


    protected function assertCollectionNegative($elementList, $showIndex = true)
    {
        foreach ($elementList as $index => $e) {
            $actualText = $e->text();
            if ($this->expected == $actualText) {
                $prefix = $showIndex ? ('Element[' . $index . ']: ') : '';
                throw new Assertion(
                    $prefix . 'Text must be NOT equal ' . $this->expected . ', actual - ' . $actualText
                );
            }
        }
        return $this;
    }

}
