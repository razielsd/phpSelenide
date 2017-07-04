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


    protected $listenerCollection = null;
    protected $description = '';


    public function __construct()
    {
        $this->listenerCollection = new ListenerCollection();
    }


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
        $selectorList = new SelectorList();
        $collection = new ElementsCollection($this, $selectorList);
        $collection->description($this->description);
        $collection->find($locator);
        $this->description = '';
        return $collection;
    }


    /**
     * Find elements collection
     *
     * @param $locator
     * @return ElementsCollection
     */
    public function findAll(By $locator)
    {
        $selectorList = new SelectorList();
        $collection = new ElementsCollection($this, $selectorList);
        $collection->description($this->description);
        $collection->findAll($locator);
        $this->description = '';
        return $collection;
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
     * @return Selenide
     */
    public function description(string $description)
    {
        $this->getListener()->beforeSetDescription($description);
        $this->description = $description;
        return $this;
    }


    /**
     * @return Selenide_Switcher
     */
    public function switchTo(): Selenide_Switcher
    {
        $switcher = new Selenide_Switcher($this);
        return $switcher;
    }


    /**
     * @param Listener $listener
     * @return $this
     */
    public function addListener(Listener $listener)
    {
        $this->listenerCollection->addListener($listener);
        return $this;
    }


    /**
     * @return ListenerCollection
     */
    public function getListener()
    {
        return $this->listenerCollection;
    }
}
