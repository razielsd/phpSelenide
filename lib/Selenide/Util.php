<?php
namespace Selenide;


class Util
{
    public static function selectorAsText(array $selectorList)
    {
        $locator = '';
        /** @var Selector $selector */
        foreach ($selectorList as $selector) {
            $locator .= empty($locator) ? '' : ' -> ';
            $locator .= $selector->asString();
        }
        return $locator;
    }

}