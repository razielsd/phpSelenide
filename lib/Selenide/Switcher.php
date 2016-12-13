<?php

namespace Selenide;

class Switcher
{

    /**
     * @var Selenide
     */
    protected $selenide = null;


    public function __construct(Selenide $selenide)
    {
        $this->selenide = $selenide;
    }


    public function defaultContent()
    {
        $this
            ->selenide
            ->getDriver()
            ->webDriver()
            ->frame()
            ->focus(null);
        return $this->selenide;
    }


    public function frame(By $selector)
    {
        $element = $this
            ->selenide
            ->getDriver()
            ->webDriver()
            ->find($selector->asString());
        $this
            ->selenide
            ->getDriver()
            ->webDriver()
            ->frame()
            ->focus($element);
        return $this->selenide;
    }
}
