<?php
namespace Selenide;

class Condition_Text extends Condition_Rule implements Condition_Interface_matchCollection
{
    protected $text = null;


    public function __construct($text)
    {
        $this->text = $text;
    }


    public function getLocator()
    {
        return $this->getName() . '(' . $this->text . ')';
    }


    public function matchCollectionPositive($collection)
    {
        $resultList = [];
        /** @var \WebDriver_Element $element */
        foreach ($collection as &$element) {
            $actualText = $element->text();
            if ($this->text == $actualText) {
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
            if ($this->text != $actualText) {
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
            if ($this->text != $actualText) {
                $prefix = $showIndex ? ('Element[' . $index . ']: ') : '';
                throw new Assertion(
                    $prefix . 'Text must be equal ' . $this->text . ', actual - ' . $actualText
                );
            }
        }
        return $this;
    }


    protected function assertCollectionNegative($elementList, $showIndex = true)
    {
        foreach ($elementList as $index => $e) {
            $actualText = $e->text();
            if ($this->text == $actualText) {
                $prefix = $showIndex ? ('Element[' . $index . ']: ') : '';
                throw new Assertion(
                    $prefix . 'Text must be NOT equal ' . $this->text . ', actual - ' . $actualText
                );
            }
        }
        return $this;
    }

}
