<?php

namespace Selenide;


class SelectorList implements \ArrayAccess, \Countable, \Iterator
{
    /**
     * @var Selector[]
     */
    protected $selectorList = [];


    /**
     * @param Selector $selector
     * @return $this
     */
    public function add(Selector $selector)
    {
        $this->selectorList[] = $selector;
        return $this;
    }


    public function get(int $index)
    {
        if (!array_key_exists($index, $this->selectorList)) {
            throw new Exception('Not found selector with index: ' . $index);
        }
        return $this->selectorList[$index];
    }


    /**
     * @return string
     */
    public function getLocator()
    {
        $locator = '';
        foreach ($this->selectorList as $selector) {
            $locator .= empty($locator) ? '' : ' -> ';
            $locator .= $selector->asString();
        }
        return $locator;
    }


    public function offsetSet($offset, $value) {
        if (is_null($offset)) {
            $this->selectorList[] = $value;
        } else {
            $this->selectorList[$offset] = $value;
        }
    }

    
    public function offsetExists($offset) {
        return isset($this->selectorList[$offset]);
    }

    
    public function offsetUnset($offset) {
        unset($this->selectorList[$offset]);
    }

    
    public function offsetGet($offset) {
        return isset($this->selectorList[$offset]) ? $this->selectorList[$offset] : null;
    }


    public function count()
    {
        return count($this->selectorList);
    }


    public function rewind()
    {
        reset($this->selectorList);
    }


    public function current()
    {
        $currentElement = current($this->selectorList);
        return $currentElement;
    }


    public function key()
    {
        $currentKey = key($this->selectorList);
        return $currentKey;
    }


    public function next()
    {
        $nextElement = next($this->selectorList);
        return $nextElement;
    }


    public function valid()
    {
        $key = key($this->selectorList);
        $isValid = ($key !== NULL && $key !== FALSE);
        return $isValid;
    }
}
