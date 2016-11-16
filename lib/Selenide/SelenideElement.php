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

    protected $description = '';


    public function __construct(Selenide $selenide, array $selectorList)
    {
        $this->selenide = $selenide;
        $this->driver = $selenide->getDriver();
        $this->selectorList = $selectorList;
    }


    /**
     * Set element description
     *
     * @param $description
     * @return $this
     */
    public function description($description)
    {
        $this->description = $description;
        return $this;
    }


    /**
     * Find single element
     *
     * @param $locator
     * @return SelenideElement
     */
    public function find(By $locator)
    {
        $selector = new Selector();
        $selector->locator = $locator->asString();
        $selector->type = Selector::TYPE_ELEMENT;
        $selectorList = $this->selectorList;
        $selectorList[] = $selector;
        $element = new SelenideElement($this->selenide, $selectorList);
        $element->description($this->description);
        return $element;
    }


    /**
     * Find elements collection
     *
     * @param $locator
     * @return ElementsCollection
     */
    public function findAll(By $locator)
    {
        $selector = new Selector();
        $selector->locator = $locator->asString();
        $selector->type = Selector::TYPE_COLLECTION;
        $selectorList = $this->selectorList;
        $selectorList[] = $selector;
        $collection = new ElementsCollection($this->selenide, $selectorList);
        $collection->description($this->description);
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
     * Assert condition
     *
     * @param Condition_Rule $condition
     * @return $this
     * @throws Exception_ElementNotFound
     */
    public function assert(Condition_Rule $condition)
    {
        $collection = $this->getCollection();
        try {
            $condition->applyAssert($collection);
        } catch (Exception_ElementNotFound $e) {
            throw new Exception_ElementNotFound(
                $this->description .
                ': Not found element ' . $this->getLocator() . ' with condition ' .
                    $condition->getLocator(),
                0,
                $e);
        }

        return $this;
    }


    /**
     * Assert not condition
     *
     * @param Condition_Rule $condition
     * @return $this
     */
    public function assertNot(Condition_Rule $condition)
    {
        $collection = $this->getCollection();
        $condition->applyAssertNegative($collection);
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
        $element = $this->getExistsElement();
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
        $element = $this->getExistsElement();
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
        $element = $this->getExistsElement();
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
        $element = $this->getExistsElement();
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
        $element = $this->getExistsElement();
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
        $element = $this->getExistsElement();
        $this->selenide->getReport()->addChildEvent('Read value');
        return $element->value();
    }


    /**
     * Get element attribute
     *
     * @param $name
     * @return string
     */
    public function attribute($name)
    {
        $element = $this->getExistsElement();
        $this->selenide->getReport()->addChildEvent('Get attribute: ' . $name);
        $attrValue =  $element->attribute($name);

        if ($attrValue === null) {
            throw new Exception_ElementNotFound('Attribute ' . $name . ' not found');
        }
        return $attrValue;
    }


    /**
     * Get element text
     *
     * @return string
     */
    public function text()
    {
        $element = $this->getExistsElement();
        $this->selenide->getReport()->addChildEvent('Get element text');
        return $element->text();
    }


    /**
     * Check exists element
     *
     * @return bool
     */
    public function exists()
    {
        try {
            $element = $this->getElement();
        } catch (\WebDriver_Exception_FailedCommand $e) {
            $element = null;
        }
        return !is_null($element);
    }


    /**
     * Check checkbox checked
     *
     * @return bool
     */
    public function checked()
    {
        $element = $this->getExistsElement();
        return $element->checked();
    }


    /**
     * Check element visible
     *
     * @return bool
     */
    public function isDisplayed()
    {
        $element = $this->getExistsElement();
        return $element->isDisplayed();
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


    /**
     * @return \WebDriver_Element
     * @throws Exception
     */
    protected function getCollection()
    {
        $elementList = [];
        $element = $this->getElement();
        if (!is_null($element)) {
            $elementList[] = $element;
        }
        return $elementList;
    }


    /**
     * Get element, when not found - throw exception
     *
     * @return \WebDriver_Element
     * @throws Exception_ElementNotFound
     */
    protected function getExistsElement()
    {
        $element = $this->getElement();
        if (!$element) {
            throw new Exception_ElementNotFound(
                $this->description .
                ': Not found element ' . $this->getLocator()
            );
        }
        return $element;
    }

}
