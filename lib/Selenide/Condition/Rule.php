<?php
namespace Selenide;


abstract class Condition_Rule
{
    protected $expected = null;


    public function __construct($expected)
    {
        $this->expected = $expected;
    }

    /**
     * Get Condition name
     *
     * @return string
     */
    public function getName()
    {
        $className = get_called_class();
        $className = preg_replace("#^[^\\\\]+\\\\#i", '', $className);
        return str_replace('_', '::', $className);
    }

    /**
     * Get string definition about filter, for example: text(auchtung)
     *
     * @return string
     */
    public function getLocator()
    {
        return $this->getName() . '(' . $this->expected . ')';
    }


    public function applyAssert(array $collection)
    {
        if ($this instanceof Condition_Interface_assertCollection) {
            $this->assertCollectionPositive($collection);
        }else {
            throw new Exception_UnsupportedConditionOperation(
                'Condition ' . $this->getName() . ' not support for assertion(shouldHave and etc)');
        }
        return $this;
    }


    public function applyAssertNegative(array $collection)
    {
        if ($this instanceof Condition_Interface_assertCollection) {
            $this->assertCollectionNegative($collection);
        }else {
            throw new Exception_UnsupportedConditionOperation(
                'Condition ' . $this->getName() . ' not support for assertion(shouldHave and etc)');
        }
        return $this;
    }


    public function match($collection, $isPositive = true){
        $result = [];
        if ($this instanceof Condition_Interface_ExpectedCollection) {
            $expected = $this->matchCollection($collection);
            $expected = $isPositive ? $expected : !$expected;
            if (!$expected) {
                throw new Exception_ConditionMatchFailed('Match collection failed, search restart');
            }
            $result = $collection;
        }  else if ($this instanceof Condition_Interface_Match) {
            $result = [];
            foreach ($collection as $element) {
                $isMatched = $this->matchElement($element);
                if ($isMatched && $isPositive) { //match positive
                    $result[] = $element;
                } else if (!$isMatched && !$isPositive) { // match negative
                    $result[] = $element;
                }
            }
        } else {
            throw new Exception('Condition ' . $this->getName() . " can't use in should()");
        }
        return $result;
    }
}
