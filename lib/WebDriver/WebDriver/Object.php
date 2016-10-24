<?php
class WebDriver_Object
{
    protected $driver = null;

    public function __construct(WebDriver_Driver $driver)
    {
        $this->driver = $driver;
    }
}
