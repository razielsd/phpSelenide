<?php
namespace Selenide;


class SelenideElement
{
    /**
     * @var Selenide;
     */
    protected $selenide = null;
    /**
     * @var Driver
     */
    protected $driver = null;
    /**
     * @var Selector[]
     */
    protected $selectorList = [];


    public function __construct(Selenide $selenide, array $selectorList)
    {
        $this->selenide = $selenide;
        $this->driver = $selenide->getDriver();
        $this->selectorList = $selectorList;
    }


    /**
     * Find single element
     *
     * @param $locator
     * @return SelenideElement
     */
    public function find($locator)
    {
        $selector = new Selector();
        $selector->locator = $locator;
        $selector->type = Selector::TYPE_ELEMENT;
        $selectorList = $this->selectorList;
        $selectorList[] = $selector;
        $element = new SelenideElement($this->selenide, $selectorList);
        return $element;
    }


    /**
     * Find elements collection
     *
     * @param $locator
     * @return ElementsCollection
     */
    public function findAll($locator)
    {
        $selector = new Selector();
        $selector->locator = $locator;
        $selector->type = Selector::TYPE_COLLECTION;
        $selectorList = $this->selectorList;
        $selectorList[] = $selector;
        $collection = new ElementsCollection($this->selenide, $selectorList);
        return $collection;
    }


    /**
     * Filter by condition
     *
     * @param Condition_Rule $condition
     * @return SelenideElement
     */
    public function should(Condition_Rule $condition)
    {
        $selector = new Selector();
        $selector->condition = $condition;
        $selector->isPositive = true;
        $this->selectorList[] = $selector;
        return $this;
    }


    /**
     * Filter by Not Condition
     *
     * @param Condition_Rule $condition
     * @return SelenideElement
     */
    public function shouldNot(Condition_Rule $condition)
    {
        $selector = new Selector();
        $selector->condition = $condition;
        $selector->isPositive = false;
        $this->selectorList[] = $selector;
        return $this;
    }


    /**
     * Assert condition (alias for shouldHave)
     *
     * @param Condition_Rule $condition
     * @return $this
     */
    public function shouldBe(Condition_Rule $condition)
    {
        return $this->shouldHave($condition);
    }


    /**
     * Assert condition
     *
     * @param Condition_Rule $condition
     * @return $this
     */
    public function shouldHave(Condition_Rule $condition)
    {
        $element = $this->getElement();
        $condition->applyAssert($element);
        return $this;
    }


    /**
     * Assert not condition (alias for shouldNotHave)
     *
     * @param Condition_Rule $condition
     * @return SelenideElement
     */
    public function shouldNotBe(Condition_Rule $condition)
    {
        return $this->shouldNotHave($condition);
    }


    /**
     * Assert not condition
     *
     * @param Condition_Rule $condition
     * @return $this
     */
    public function shouldNotHave(Condition_Rule $condition)
    {
        $element = $this->getElement();
        $condition->applyAssertNegative($element);
        return $this;
    }


    /**
     * Set element value
     *
     * @param $value
     * @return $this
     */
    public function setValue($value)
    {
        $element = $this->getElement();
        $this->selenide->getReport()->addChildEvent('Set value: ' . $value);
        $element->value($value);
        return $this;
    }


    /**
     * Press key enter
     *
     * @return $this
     */
    public function pressEnter()
    {
        $driver = $this->driver->webDriver();
        $element = $this->getElement();
        $this->selenide->getReport()->addChildEvent('press Enter');
        $element->keys([$driver::KEY_ENTER]);
        return $this;
    }


    /**
     * Click on element
     *
     * @return $this
     */
    public function click()
    {
        $element = $this->getElement();
        $this->selenide->getReport()->addChildEvent('Click');
        $element->click();
        return $this;
    }


    /**
     * Click on element
     *
     * @return $this
     */
    public function doubleClick()
    {
        $element = $this->getElement();
        $this->selenide->getReport()->addChildEvent('Double click');
        $element->dbclick();
        return $this;
    }


    /**
     * Select option by option text
     *
     * @param $text
     * @return $this
     */
    public function selectOption($text)
    {
        throw new Exception(__METHOD__ . ' - under construction');
        //$element = $this->getElement();
        //$this->selenide->getReport()->addChildEvent('selectOption');
        //$element->value();
        return $this;
    }


    /**
     * Select option by value
     *
     * @param $value
     * @return $this
     */
    public function selectOptionByValue($value)
    {
        $element = $this->getElement();
        $this->selenide->getReport()->addChildEvent('Set element value: ' . $value);
        $element->value($value);
        return $this;
    }


    /**
     * Get element value
     *
     * @return string
     */
    public function val()
    {
        $element = $this->getElement();
        $this->selenide->getReport()->addChildEvent('Read value');
        return $element->value();
    }


    /**
     * Get element attribute
     *
     * @param $name
     * @return $this
     */
    public function attribute($name)
    {
        $element = $this->getElement();
        $this->selenide->getReport()->addChildEvent('Get attribute: ' . $name);
        $element->attribute($name);
        return $this;
    }


    /**
     * Get element text
     *
     * @return string
     */
    public function text()
    {
        $element = $this->getElement();
        $this->selenide->getReport()->addChildEvent('Get element text');
        return $element->text();
    }


    /**
     * Get path for element
     *
     * @return string
     */
    public function getLocator()
    {
        return Util::selectorAsText($this->selectorList);
    }


    /**
     * @return \WebDriver_Element
     * @throws Exception
     */
    protected function getElement()
    {
        $this->selenide->getReport()->addElement($this);
        $elementList = $this->driver->search($this->selectorList);
        $element = isset($elementList[0]) ? $elementList[0] : null;
        $stateText = $element ? 'Found element' : 'Not found element';
        $this->selenide->getReport()->addChildEvent($stateText);
        return $element;
    }

}
