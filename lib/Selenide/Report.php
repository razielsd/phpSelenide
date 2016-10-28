<?php
namespace Selenide;


class Report
{
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
        echo 'Selenide: ' . $text . "\n";
    }


    public function addChildEvent($text)
    {
        echo 'Selenide: -->' . $text . "\n";
    }
}