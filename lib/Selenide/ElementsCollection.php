<?php
namespace Selenide;

class ElementsCollection extends SelenideElement
{
    public function shouldHave(Condition_Rule $condition)
    {
        $collection = $this->getCollection();
        $condition->applyAssert($collection);
        return $this;
    }


    public function shouldNotHave(Condition_Rule $condition)
    {
        $collection = $this->getCollection();
        $condition->applyAssertNegative($collection);
        return $this;
    }


    /**
     * Check element visible
     *
     * @return bool
     */
    public function isDispayed()
    {
        $collection = $this->getCollection();
        $counter = 0;
        foreach ($collection as $element) {
            $counter += $element->isDisplayed() ? 1 : 0;
        }
        return count($collection) == $counter;
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