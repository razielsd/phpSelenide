<?php
namespace Selenide;


abstract class Condition_Rule
{
    /**
     * Get Condition name
     *
     * @return string
     */
    public function getName()
    {
        $className = get_called_class();
        return str_replace('_', '::', $className);
    }

    /**
     * Get string definition about filter, for example: text(auchtung)
     *
     * @return string
     */
    abstract public function getLocator();


    public function applyAssert($element)
    {
        if (is_object($element)) {
            $this->assertElement($element);
        } else {
            $this->assertCollection($element);
        }
        return $this;
    }


    public function applyAssertNegative($element)
    {
        if (is_object($element)) {
            $this->assertElementNegative($element);
        } else {
            $this->assertCollectionNegative($element);
        }
        return $this;
    }


    public function match($collection, $isPositive = true){
        if ($this instanceof Condition_Interface_matchCollection) {
            if ($isPositive) {
                $result = $this->matchCollectionPositive($collection);
            } else {
                $result = $this->matchCollectionNegative($collection);
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


    protected function assertCollection($elementList)
    {
        throw new Exception(
            'Unsupported condition ' . get_called_class() . ' for ElementsCollection'
        );
    }


    protected function assertCollectionNegative($element)
    {
        throw new Exception(
            'Unsupported condition ' . get_called_class() . ' for ElementsCollection'
        );
    }


}
