<?php
namespace Selenide;


class SelenideElement
{
    const FILTER_NEGATIVE = 1;
    const FILTER_POSITIVE = 2;

    const ELEMENT_SINGLE = 1;
    const ELEMENT_LIST = 2;

    /**
     * @var \WebDriver_Element;
     */
    protected $wdElement = null;
    /**
     * @var \WebDriver
     */
    protected $driver = null;
    /**
     * @var Selector[]
     */
    protected $selectorList = [];


    public function __construct($driver, Selector $selector)
    {
        $this->driver = $driver;
        $this->selectorList[] = $selector;
    }


    /**
     * @param Condition_Rule $condition
     * @return SelenideElement
     */
    public function should(Condition_Rule $condition)
    {
        $this->selectorList[] = [
            'condition' => $condition,
            'type' => self::FILTER_POSITIVE
        ];
        return $this;
    }


    /**
     * @param Condition_Rule $condition
     * @return SelenideElement
     */
    public function shouldNot(Condition_Rule $condition)
    {
        $this->selectorList[] = [
            'condition' => $condition,
            'type' => self::FILTER_NEGATIVE
        ];
        return $this;
    }


    /**
     * Alias for shouldHave
     *
     * @param Condition_Rule $condition
     * @return $this
     */
    public function shouldBe(Condition_Rule $condition)
    {
        return $this->shouldHave($condition);
    }


    public function shouldHave(Condition_Rule $condition)
    {
        $element = $this->getElement();
        $condition->apply($element);
        return $this;
    }


    public function shouldNotBe(Condition_Rule $condition)
    {

    }


    public function shouldNotHave(Condition_Rule $condition)
    {

    }


    public function setValue($value)
    {
        $this->getElement()->value($value);
        return $this;
    }


    public function pressEnter()
    {
        $driver = $this->driver;
        $this->getElement()->keys([$driver::KEY_ENTER]);
        return $this;
    }


    /**
     * @return \WebDriver_Element
     * @throws Exception
     */
    protected function getElement()
    {
        foreach ($this->selectorList as $index => $selector) {
            if ($index > 0) {
                throw new Exception('Unsupported chain selectors');
            }
            $method = $selector->isSingle ? 'find' : 'findAll';
            $element = $this->driver->$method($selector->locator);
        }
        return $element;
    }

}
