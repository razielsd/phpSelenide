<?php
namespace Selenide;

/**
 * @property bool $isPositive
 * @property By $locator
 * @property int $type
 * @property Condition_Rule $condition
 */
class Selector
{
    /**
     * Select single element
     */
    const TYPE_ELEMENT = 1;
    /**
     * Select element collection
     */
    const TYPE_COLLECTION = 2;
    /**
     * Filter element(s) by condition
     */
    const TYPE_CONDITION = 3;

    protected $data = [
        'isPositive' => true,
        'locator' => null,
        'type' => self::TYPE_ELEMENT,
        'condition' => null,
    ];


    public function __get($property)
    {
        if (!array_key_exists($property, $this->data)) {
            throw new Exception("Access to unknown property {$property}");
        }
        return $this->data[$property];
    }


    public function __set($property, $value)
    {
        if (!array_key_exists($property, $this->data)) {
            throw new Exception("Access to unknown property {$property}");
        }
        $onChangeHandler = 'handlerOnChange' . ucfirst($property);
        if (method_exists($this, $onChangeHandler)) {
            $this->$onChangeHandler($value);
        }
        $this->data[$property] = $value;
    }


    public function __isset($property)
    {
        return array_key_exists($property, $this->data);
    }


    public function asString()
    {
        $locator = $this->locator;
        if ($this->condition) {
            $locator = $this->condition->getLocator();
        }
        if (!$this->isPositive) {
            $locator = 'Not(' . $locator . ')';
        }
        return $locator;
    }


    protected function handlerOnChangeCondition()
    {
        $this->type = self::TYPE_CONDITION;
    }

}
