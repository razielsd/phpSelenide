<?php

namespace Selenide;


class Selector_Locator extends Selector
{
    public function __construct(string $locator)
    {
        $this->locator = $locator;
        $this->type = self::TYPE_LOCATOR;
    }


    public function asString(): string
    {
        return $this->locator;
    }

}
