<?php
namespace Selenide;


class Selenide
{
    /**
     * @var \WebDriver
     */
    protected $webDriver = null;

    public function connect()
    {
        $this->webDriver = \WebDriver::factory();

        $driver = \WebDriver_Driver::factory(
            '127.0.0.1', 4444, null
        );
        $this->webDriver->setDriver($driver);

        $this->webDriver->getDriver()->setDesiredCapability(
            'browserName', 'firefox'
        );
        $this->webDriver->connect();
    }


    /**
     * @param $locator
     * @return SelenideElement
     */
    public function find($locator)
    {
        $selector = new Selector();
        $selector->locator = $locator;
        $selector->isSingle = true;
        return new SelenideElement($this->webDriver, $selector);
    }


    /**
     * @param $locator
     * @return ElementsCollection
     */
    public function findAll($locator)
    {
        $selector = new Selector();
        $selector->locator = $locator;
        $selector->isSingle = false;
        return new ElementsCollection($this->webDriver, $selector);
    }


    public function open($url)
    {
        $this->webDriver->url($url);
    }
}
