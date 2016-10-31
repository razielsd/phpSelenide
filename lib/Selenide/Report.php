<?php
namespace Selenide;


class Report
{

    protected $isEnabled = false;

    public function addCommand($commandLine)
    {
        $this->addRootEvent($commandLine);
    }


    public function addElement(SelenideElement $element)
    {
        $locator = $element->getLocator();
        $this->addRootEvent('GET ' . $locator);
    }


    public function addAssert($text)
    {
        $this->addChildEvent($text);
    }


    public function addRootEvent($text)
    {
        $this->write('Selenide: ' . $text);
    }


    public function addChildEvent($text)
    {
        $this->write('Selenide: -->' . $text);
    }


    /**
     * Enable detailed log
     */
    public function enable()
    {
        $this->isEnabled = true;
    }


    public function disable()
    {
        $this->isEnabled = false;
    }


    protected function write($text)
    {
        if ($this->isEnabled) {
            echo $text . "\n";
        }
    }
}