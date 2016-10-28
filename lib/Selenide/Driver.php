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
        $this->selenide->getReport()
            ->addRootEvent('Search element: ' . Util::selectorAsText($selectorList));
        $resultList = [];
        $currentSelector = [];
        foreach ($selectorList as $index => $selector) {
            $this->selenide->getReport()->addChildEvent('Match: ' . $selector->asString());
            $this->debugLog(__METHOD__ . ': Iteration ' . $index);
            $this->debugLog('Selector::type = ' . $selector->type);
            $currentSelector[] = $selector;
            if ($index == 0) {
                switch ($selector->type) {
                    case Selector::TYPE_ELEMENT:
                        $resultList = [$this->webDriver()->find($selector->locator)];
                        break;
                    case Selector::TYPE_COLLECTION:
                        $resultList = $this->webDriver()->findAll($selector->locator);
                        break;
                    default:
                        throw new Exception('Logic error: unable search start from condition');
                }
            } else {
                switch ($selector->type) {
                    case Selector::TYPE_ELEMENT:
                        $resultList = $this->searchChild($resultList, $selector);
                        break;
                    case Selector::TYPE_COLLECTION:
                        $resultList = $this->searchAllChild($resultList, $selector);
                        break;
                    case Selector::TYPE_CONDITION:
                        $resultList = $this->searchByCondition($resultList, $selector);
                        break;
                    default:
                        throw new Exception(
                            'Unknown value for Selector::type = ' . $selector->type
                        );
                }
            }
            $this->selenide->getReport()->addChildEvent('Found: ' . count($resultList));
            $this->debugLog('Found: ' . count($resultList));
        }
        return $resultList;
    }


    protected function searchChild($elementList, Selector $selector)
    {
        $resultList = [];
        /** @var \WebDriver_Element $element */
        foreach ($elementList as $element) {
            try {
                $node = $element->child($selector->locator);
                $resultList[] = $node;
                break;//found node
            } catch (WebDriver_Exception $e) {
                //not found, search in next element
            }
        }
        return $resultList;
    }


    protected function searchAllChild($elementList, $selector)
    {
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