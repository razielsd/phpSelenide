<?php
namespace Selenide;


class Driver
{
    /**
     * @var Selenide
     */
    protected $selenide = null;
    /**
     * @var \WebDriver
     */
    protected $webDriver = null;


    public $isDebug = false;


    public function __construct(Selenide $selenide)
    {
        $this->selenide = $selenide;
    }


    public function connect()
    {
        $this->webDriver = \WebDriver::factory();

        $driver = \WebDriver_Driver::factory(
            $this->selenide->configuration()->host, $this->selenide->configuration()->port, null
        );
        $this->webDriver->setDriver($driver);

        $this->webDriver->getDriver()->setDesiredCapability(
            'browserName', 'firefox'
        );
        $this->webDriver->connect($this->selenide->configuration()->sessionId);
    }


    /**
     * @return \WebDriver
     */
    public function webDriver()
    {
        return $this->webDriver;
    }


    /**
     * @param Selector[] $selectorList
     * @return \WebDriver_Element[]
     *
     * @throws Exception
     */
    public function search($selectorList) {
        $timeout = $this->selenide->configuration()->timeout;
        $startTime = microtime(true);
        $searchTimeout = 1;
        $isFound = false;
        while (!$isFound) {
            try {
                $resultList = $this->searchBySelectors($selectorList);
                $isFound = true;
            } catch (Exception_ConditionMatchFailed $e) {
                $currentTime = microtime(true);
                if (($currentTime - $startTime) <= $timeout) {
                    sleep($searchTimeout);
                } else {
                    return [];
                }
                $this->selenide->getReport()->addChildEvent(
                    'Received restart from condition: ' . $e->getMessage()
                );
            }
        }
        $this->selenide->getReport()->addChildEvent('Found: ' . count($resultList));
        return $resultList;
    }


    protected function searchBySelectors(array $selectorList)
    {
        $this->selenide->getReport()
            ->addRootEvent('Search element: ' . Util::selectorAsText($selectorList));
        $resultList = [];
        $currentSelector = [];
        foreach ($selectorList as $index => $selector) {
            $this->selenide->getReport()->addChildEvent('Match: ' . $selector->asString());
            $currentSelector[] = $selector;
            $foundElement = false;
            while (!$foundElement) {
                if ($index == 0) {
                    $resultList = $this->searchFirstElement($selector);
                } else {
                    $resultList = $this->searchFromSecondElement($resultList, $selector);
                }
                $foundElement = true;
            }
            try {
                foreach ($resultList as &$element) {
                    //refresh elements
                    $element->getElementId();
                }
            } catch (\WebDriver_Exception_FailedCommand $e) {
                throw new Exception_ConditionMatchFailed('Not found elements on synchronize with page');
            }
            $this->selenide->getReport()->addChildEvent('Found: ' . count($resultList));
            if (empty($resultList)) {
                break;
            }
        }

        return $resultList;
    }



    protected function searchFirstElement($selector)
    {
        // @todo probable error: can't detect  bad locator
        try {
            switch ($selector->type) {
                case Selector::TYPE_ELEMENT:
                    $element = $this->webDriver()->find($selector->locator);
                    $resultList = [$element];
                    break;
                case Selector::TYPE_COLLECTION:
                    $resultList = $this->webDriver()->findAll($selector->locator);
                    break;
                default:
                    throw new Exception('Logic error: unable search start from condition');
            }
        } catch (\WebDriver_Exception $e) {
            throw new Exception_ConditionMatchFailed('Find element failed, search restart');
        }
        return $resultList;
    }


    protected function searchFromSecondElement($resultList, $selector)
    {
        // @todo probable error: can't detect  bad locator
        try {
            switch ($selector->type) {
                case Selector::TYPE_ELEMENT:
                case Selector::TYPE_COLLECTION:
                    $resultList = $this->searchChild($resultList, $selector);
                    break;
                case Selector::TYPE_CONDITION:
                    $resultList = $this->searchByCondition($resultList, $selector);
                    break;
                default:
                    throw new Exception(
                        'Unknown value for Selector::type = ' . $selector->type
                    );
            }
        } catch (\WebDriver_Exception $e) {
            throw new Exception_ConditionMatchFailed('Find element failed, search restart');
        }
        return $resultList;
    }


    protected function searchChild($elementList, $selector)
    {
        $this->selenide->getReport()->addChildEvent('Search all child');
        $resultList = [];
        /** @var \WebDriver_Element $element */
        foreach ($elementList as $element) {
            try {
                $nodeList = $element->childAll($selector->locator);
                foreach ($nodeList as $node) {
                    $resultList[] = $node;
                }
            } catch (WebDriver_Exception $e) {
                //not found, search in next element
            }
        }
        return $resultList;
    }


    protected function searchByCondition($elementList, Selector $selector)
    {
        $resultList = $selector->condition->match($elementList, $selector->isPositive);
        return $resultList;
    }


    protected function debugLog($message)
    {
        if ($this->isDebug) {
            echo $message . "\n";
        }
    }

}