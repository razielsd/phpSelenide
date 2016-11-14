<?php
namespace Selenide;

class ElementsCollection extends SelenideElement
{
    public function assert(Condition_Rule $condition)
    {
        $collection = $this->getCollection();
        $condition->applyAssert($collection);
        return $this;
    }


    public function assertNot(Condition_Rule $condition)
    {
        $collection = $this->getCollection();
        $condition->applyAssertNegative($collection);
        return $this;
    }


    /**
     * Check all elements visible
     *
     * @return bool
     */
    public function isDisplayed()
    {
        $collection = $this->getCollection();
        $counter = 0;
        /** @var \WebDriver_Element $element */
        foreach ($collection as $element) {
            $counter += $element->isDisplayed() ? 1 : 0;
        }
        return count($collection) == $counter;
    }


    /**
     * Click all elements
     *
     * @return $this
     */
    public function click()
    {
        $collection = $this->getCollection();
        /** @var \WebDriver_Element $element */
        foreach ($collection as $element) {
            $element->click();
        }
        return $this;
    }


    /**
     * DoubleClick all elements
     *
     * @return $this
     */
    public function doubleClick()
    {
        $collection = $this->getCollection();
        /** @var \WebDriver_Element $element */
        foreach ($collection as $element) {
            $element->dbclick();
        }
        return $this;
    }


    /**
     * Check all elements exists
     *
     * @return bool
     */
    public function exists()
    {
        $collection = $this->getCollection();
        $counter = 0;
        /** @var \WebDriver_Element $element */
        foreach ($collection as $element) {
            $counter += $element->isPresent() ? 1 : 0;
        }
        return (count($collection) == $counter) && ($counter > 0);
    }


    /**
     * Check all elements checked
     *
     * @return bool
     */
    public function checked()
    {
        $collection = $this->getCollection();
        $counter = 0;
        /** @var \WebDriver_Element $element */
        foreach ($collection as $element) {
            $counter += $element->checked() ? 1 : 0;
        }
        return (count($collection) == $counter) && ($counter > 0);
    }


    /**
     * Get all elements attribute with name
     *
     * @return array
     */
    public function attribute($name)
    {
        $collection = $this->getCollection();
        $attrList = [];
        /** @var \WebDriver_Element $element */
        foreach ($collection as $element) {
            $attrList[] = $element->attribute($name);
        }
        return $attrList;
    }


    /**
     * @return \WebDriver_Element
     * @throws Exception
     */
    protected function getCollection()
    {
        $elementList = $this->driver->search($this->selectorList);
        $stateText = empty($elementList) ?
            'Not found elements' : ('Found elements ' . count($elementList));
        $this->selenide->getReport()->addChildEvent($stateText);
        return $elementList;
    }
}