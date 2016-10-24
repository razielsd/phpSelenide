<?php

abstract class WebDriver_Capabilities
{
    protected $data = [];
    protected $indexList = [];
    protected $position = 0;


    public function __construct()
    {
        $this->indexList = array_keys($this->data);
    }

    public function asArray()
    {
        return array_filter(
            $this->data,
            function($v) {
                return $v !== null;
            }
        );
    }


    public function __set($property, $value)
    {
        if (array_key_exists($property, $this->data)) {
            $this->data[$property] = $value;
        } else {
            throw new WebDriver_Exception('Unknown capability property: ' . $property);
        }
    }


    public function __get($property)
    {
        if (!array_key_exists($property, $this->data)) {
            throw new WebDriver_Exception('Unknown capability property: ' . $property);
        }
        return $this->data[$property];
    }

}
