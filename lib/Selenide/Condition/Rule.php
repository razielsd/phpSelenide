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


    public function applyAssert($element)
    {
        if (is_object($element)) {
            $this->assertElement($element);
        } else if (is_array($element)) {
            $this->assertCollection($element);
        } else {
            throw new Exception_ElementNotFound('Not found element');
        }
        return $this;
    }


    public function applyAssertNegative($element)
    {
        if (is_object($element)) {
            $this->assertElementNegative($element);
        } else if(is_array($element)) {
            $this->assertCollectionNegative($element);
        } else {
        throw new Exception_ElementNotFound('Not found element');
        }
        return $this;
    }


    public function match($collection, $isPositive = true){
        $result = [];
        if (empty($collection)) {
            return $result;
        }
        if ($this instanceof Condition_Interface_MatchCollection) {
            $result = $isPositive ?
                $this->matchCollectionPositive($collection) : $this->matchCollectionNegative($collection);
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


    protected function assertElement($element)
    {
        throw new Exception(
            'Unsupported condition ' . get_called_class() . ' for single element'
        );
    }


    protected function assertElementNegative($element)
    {
        throw new Exception(
            'Unsupported condition ' . get_called_class() . ' for single element'
        );
    }


    protected function assertCollection(array $elementList)
    {
        throw new Exception(
            'Unsupported condition ' . get_called_class() . ' for ElementsCollection'
        );
    }


    protected function assertCollectionNegative(array $element)
    {
        throw new Exception(
            'Unsupported condition ' . get_called_class() . ' for ElementsCollection'
        );
    }


}
