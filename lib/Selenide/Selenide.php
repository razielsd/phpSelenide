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
    /**
     * Server host for test
     * @var string
     */
    protected $host = '';

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
     * @return ElementsCollection
     */
    public function find(By $locator)
    {
        $selector = new Selector();
        $selector->locator = $locator->asString();
        $selector->type = Selector::TYPE_ELEMENT;
        return new ElementsCollection($this, [$selector]);
    }


    /**
     * Find elements collection
     *
     * @param $locator
     * @return ElementsCollection
     */
    public function findAll(By $locator)
    {
        $selector = new Selector();
        $selector->locator = $locator->asString();
        $selector->type = Selector::TYPE_COLLECTION;
        return new ElementsCollection($this, [$selector]);
    }


    /**
     * Open url
     *
     * @param $url
     * @return $this
     *
     * @throws Exception
     */
    public function open($url)
    {
        $openUrl = $this->configuration()->baseUrl . $url;
        $this->getReport()->addCommand('Open ' . $openUrl);
        try {
            $this->driver->webDriver()->url($openUrl);
        } catch (\WebDriver_Exception_FailedCommand $e){
            throw new Exception('Error open url: ' . $openUrl, 0, $e);
        }
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
     * Execute javascript
     *
     * @param $script - javascript source
     * @param array $elementList
     * @return mixed
     */
    public function execute($script, array $elementList = [])
    {
        $params = [];
        foreach ($elementList as $element) {
            $params[]['ELEMENT'] = $element->getElementId();
        }
        return $this->getDriver()->webDriver()->execute(
            $script, $params
        );

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


    /**
     * Create element with description
     *
     * @param $description
     * @return ElementsCollection
     */
    public function description($description)
    {
        $element = new ElementsCollection($this, []);
        $element->description($description);
        return $element;
    }


    public function focus(By $selector)
    {
        $element = $this->getDriver()->webDriver()->find($selector->asString());
        $this->getDriver()->webDriver()->frame()->focus($element);
        return $this;
    }
}
