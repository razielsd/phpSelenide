<?php

namespace Selenide;

use ArrayAccess;
use Countable;
use Iterator;

class ElementsCollection implements Iterator, Countable, ArrayAccess
{
    const MODE_SINGLE_ELEMENT = 1;
    const MODE_COLLECTION_ELEMENT = 2;

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
    protected $elementCache = null;

    /**
     * @var int Current position for Iterator interface.
     */
    protected $index = 0;


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
     * @return ElementsCollection
     */
    public function find(By $locator)
    {
        $this->clearCache();
        $selector = new Selector();
        $selector->locator = $locator;
        $selector->type = Selector::TYPE_ELEMENT;
        $this->selectorList[] = $selector;
        return $this;
    }


    /**
     * Find elements collection
     *
     * @param $locator
     * @return ElementsCollection
     */
    public function findAll(By $locator)
    {
        $this->clearCache();
        $selector = new Selector();
        $selector->locator = $locator;
        $selector->type = Selector::TYPE_COLLECTION;
        $this->selectorList[] = $selector;
        return $this;
    }


    /**
     * Filter by condition
     *
     * @param Condition_Rule $condition
     * @return ElementsCollection
     */
    public function should(Condition_Rule $condition)
    {
        $this->clearCache();
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
     * @return ElementsCollection
     */
    public function shouldNot(Condition_Rule $condition)
    {
        $this->clearCache();
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
        $collection = $this->getWebdriverCollection();
        $collection = $this->prepareResult($collection, true);
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
        $collection = $this->getWebdriverCollection();
        $collection = $this->prepareResult($collection, true);
        $condition->applyAssertNegative($collection);
        return $this;
    }


    public function wait(Condition_Rule $condition, $waitTimeout = null)
    {
        $this->clearCache();
        $selectorList = $this->selectorList;
        $waitTimeout = $waitTimeout ?? $this->selenide->configuration()->waitTimeout;
        $this->should($condition);
        try {
            $timeout = $this->selenide->configuration()->timeout;
            $this->selenide->configuration()->timeout = $waitTimeout;
            $this->getWebdriverCollection();
        } finally {
            $this->selenide->configuration()->timeout = $timeout;
            $this->selectorList = $selectorList;
        }
        return $this;
    }


    /**
     * @param int $index
     * @return SelenideElement
     * @throws Exception_ElementNotFound
     */
    public function get($index = 0)
    {
        $collection = $this->getCollectionNotEmpty();
        if (!isset($collection[$index])) {
            throw new Exception_ElementNotFound(
                $this->description . ': Not found element ' . $this->getLocator()
            );
        }
        return $collection[$index];
    }


    public function length()
    {
        return count($this->getCollection());
    }


    /**
     * Set element value
     *
     * @param $value
     * @return $this
     */
    public function setValue($value)
    {
        $collection = $this->getCollectionNotEmpty();
        foreach ($collection as $element) {
            $element->setValue($value);
        }
        return $this;
    }


    /**
     * Press key enter
     *
     * @return $this
     */
    public function pressEnter()
    {
        $collection = $this->getCollectionNotEmpty();
        foreach ($collection as $element) {
            $element->pressEnter();
        }
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
        foreach ($collection as $element) {
            $counter += $element->isDisplayed() ? 1 : 0;
        }
        return ((count($collection) == $counter) && ($counter > 0));
    }


    /**
     * Click all elements
     *
     * @return $this
     */
    public function click()
    {
        $collection = $this->getCollectionNotEmpty();
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
        $collection = $this->getCollectionNotEmpty();
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
        foreach ($collection as $element) {
            $counter += $element->exists() ? 1 : 0;
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
        $collection = $this->getCollectionNotEmpty();
        $counter = 0;
        foreach ($collection as $element) {
            $counter += $element->checked() ? 1 : 0;
        }
        return (count($collection) == $counter) && ($counter > 0);
    }


    /**
     * Get element list values
     *
     * @return string|string[]
     */
    public function val()
    {
        $collection = $this->getCollectionNotEmpty();
        $this->selenide->getReport()->addChildEvent('Read value');
        $valueList = [];
        foreach ($collection as $element) {
            $valueList[] = $element->val();
        }
        return $this->prepareResult($valueList);
    }



    /**
     * Get all elements attribute with name
     *
     * @return string|string[]
     */
    public function attribute($name)
    {
        $collection = $this->getCollectionNotEmpty();
        $attrList = [];
        foreach ($collection as $element) {
            $attrList[] = $element->attribute($name);
        }
        return $this->prepareResult($attrList);
    }


    /**
     * Get element list values
     *
     * @return string|string[]
     */
    public function text()
    {
        $collection = $this->getCollectionNotEmpty();
        $this->selenide->getReport()->addChildEvent('Read value');
        $valueList = [];
        foreach ($collection as $element) {
            $valueList[] = $element->text();
        }
        return $this->prepareResult($valueList);
    }


    /**
     * Execute javascript for elements collection
     *
     * @param $script
     * @return mixed
     */
    public function execute($script)
    {
        return $this->selenide->execute($script, $this->getCollection());
    }


    public function source()
    {
        return $this->execute('return arguments[0].outerHTML;');
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
     * @return \WebDriver_Element[]
     */
    protected function getWebdriverCollection()
    {
        if ($this->elementCache === null) {
            $elementList = $this->driver->search($this->selectorList);
            $stateText = empty($elementList) ?
                'Not found elements' : ('Found elements ' . count($elementList));
            $this->selenide->getReport()->addChildEvent($stateText);
            $this->elementCache = $elementList;
        }
        return $this->elementCache;
    }


    /**
     * @return SelenideElement[]
     */
    public function getCollection()
    {
        $elementList = $this->getWebdriverCollection();
        $resultList = [];
        foreach ($elementList as $wdElement) {
            $resultList[] = new SelenideElement($this->selenide, $wdElement);
        }
        return $resultList;
    }


    /**
     * Alias for getCollection with check for not empty
     *
     * @return SelenideElement[]
     * @throws Exception_ElementNotFound
     */
    public function getCollectionNotEmpty()
    {
        $collection = $this->getCollection();
        if (empty($collection)) {
            throw new Exception_ElementNotFound(
                $this->description .
                ': Not found element ' . $this->getLocator()
            );
        }
        return $collection;
    }


    protected function clearCache()
    {
        $this->elementCache = null;
    }


    protected function prepareResult(array $result, $asArray = false)
    {
        $mode = self::MODE_COLLECTION_ELEMENT;
        foreach ($this->selectorList as $selector) {
            switch ($selector->type) {
                case Selector::TYPE_ELEMENT:
                    $mode = self::MODE_SINGLE_ELEMENT;
                    break;
                case Selector::TYPE_COLLECTION:
                    $mode = self::MODE_COLLECTION_ELEMENT;
                    break;
            }
        }

        if ($mode != self::MODE_COLLECTION_ELEMENT) {
            $result = isset($result[0]) ? $result[0] : null;
            if ($asArray) {
                $result = $result ? [$result] : [];
            }
        }
        return $result;
    }


    /**
     * @return SelenideElement
     */
    public function current(): SelenideElement
    {
        return $this->get($this->index);
    }


    /**
     * @return void
     */
    public function next()
    {
        $this->index++;
    }


    /**
     * @return int
     */
    public function key()
    {
        return $this->index;
    }


    /**
     * @return boolean
     */
    public function valid()
    {
        return array_key_exists($this->index, $this->getCollection());
    }


    /**
     * @return void
     */
    public function rewind()
    {
        $this->index = 0;
    }


    /**
     * @return int
     */
    public function count()
    {
        return $this->length();
    }


    /**
     * @param int $offset
     * @return boolean
     */
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->getCollection());
    }


    /**
     * @param int $offset
     * @return SelenideElement
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }


    /**
     * @param mixed $offset
     * @param mixed $value
     * @throws Exception_CollectionMethodNotImplemented
     */
    public function offsetSet($offset, $value)
    {
        throw new Exception_CollectionMethodNotImplemented();
    }


    /**
     * @param mixed $offset
     * @throws Exception_CollectionMethodNotImplemented
     */
    public function offsetUnset($offset)
    {
        throw new Exception_CollectionMethodNotImplemented();
    }
}
