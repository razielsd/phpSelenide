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