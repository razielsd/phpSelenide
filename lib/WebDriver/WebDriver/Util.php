<?php

class WebDriver_Util
{


    public static function parseLocator($locator)
    {
        $strategyList = array(
            'class' => 'class name',
            'css' => 'css selector',
            'id' => 'id',
            'name' => 'name',
            'link' => 'link text',
            'partial_link' => 'partial link text',
            'tag' => 'tag name',
            'xpath' => 'xpath',
            'ios_uiautomation' => '-ios uiautomation',
            'xui' => 'xui',
        );
        $info = explode('=', $locator, 2);
        if (count($info) != 2) {
            throw new WebDriver_UtilException("Bad locator format, required <strategy>=<search>, locator: {$locator}");
        }
        $strategy = $info[0];
        if (!isset($strategyList[$strategy])) {
            throw new WebDriver_UtilException("Unknown locator strategy {$strategy} for locator: {$locator}");
        }
        return ['using' => $strategyList[$strategy], 'value' => $info[1]];
    }


    /**
     * Translates char list into keystroke sequence.
     *
     * @param mixed $charList
     * @return array
     * @throws WebDriver_Exception
     */
    public static function prepareKeyStrokes($charList)
    {
        $keyStrokes = [];
        foreach ((array) $charList as $char) {
            if (is_int($char)) {
                // treat int as 16-bit little-endian Unicode
                if (false === $symbol = iconv('UTF-16LE', 'UTF-8', pack('v', $char))) {
                    throw new WebDriver_UtilException("Invalid Unicode symbol: U+" . strtoupper(dechex($char)));
                }
                $keyStrokes[] = $symbol;
            } else {
                $keyStrokes[] = (string) $char;
            }
        }
        if (empty($keyStrokes)) {
            throw new WebDriver_UtilException("No keystrokes to send");
        }
        return $keyStrokes;
    }
}
