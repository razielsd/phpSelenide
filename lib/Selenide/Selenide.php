<?php
namespace Selenide;


class Selenide
{
    /**
     * @var Driver
     */
    protected $driver = null;
    /**
     * @var Configuration
     */
    protected $configuration = null;
    /**
     * @var Report
     */
    protected $report = null;

    public function connect()
    {
        $this->driver = new Driver($this);
        $this->driver->connect();
        return $this;
    }


    /**
     * Find single element
     *
     * @param $locator
     * @return SelenideElement
     */
    public function find($locator)
    {
        $selector = new Selector();
        $selector->locator = $locator;
        $selector->type = Selector::TYPE_ELEMENT;
        return new SelenideElement($this, [$selector]);
    }


    /**
     * Find elements collection
     *
     * @param $locator
     * @return ElementsCollection
     */
    public function findAll($locator)
    {
        $selector = new Selector();
        $selector->locator = $locator;
        $selector->type = Selector::TYPE_COLLECTION;
        return new ElementsCollection($this, [$selector]);
    }


    /**
     * Open url
     *
     * @param $url
     * @return $this
     */
    public function open($url)
    {
        $this->getReport()->addCommand('Open ' . $url);
        $this->driver->webDriver()->url($url);
        return $this;
    }


    /**
     * @return Configuration
     */
    public function configuration()
    {
        if (!$this->configuration) {
            $this->configuration = new Configuration();
        }
        return $this->configuration;
    }


    /**
     * @return Report
     */
    public function getReport()
    {
        if (!$this->report) {
            $this->report = new Report();
        }
        return $this->report;
    }


    /**
     * @return Driver
     */
    public function getDriver()
    {
        return $this->driver;
    }

}
