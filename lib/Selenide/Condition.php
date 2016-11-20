<?php
namespace Selenide;

class Condition
{
    /**
     * Checks that collection has the given size
     *
     * @param $size
     * @return Condition_Size
     */
    public static function size($size)
    {
        return new Condition_Size($size);
    }


    /**
     * Checks that collection has the given size
     *
     * @param $size
     * @return Condition_SizeGreaterThen
     */
    public static function sizeGreaterThen($size)
    {
        return new Condition_SizeGreaterThen($size);
    }


    /**
     * Checks that collection has the given size
     *
     * @param $size
     * @return Condition_SizeGreaterThenOrEqual
     */
    public static function sizeGreaterThenOrEqual($size)
    {
        return new Condition_SizeGreaterThenOrEqual($size);
    }


    /**
     * Checks that collection has the given size
     *
     * @param $size
     * @return Condition_SizeLessThen
     */
    public static function sizeLessThen($size)
    {
        return new Condition_SizeLessThen($size);
    }


    /**
     * Checks that collection has the given size
     *
     * @param $size
     * @return Condition_SizeLessThenOrEqual
     */
    public static function sizeLessThenOrEqual($size)
    {
        return new Condition_SizeLessThenOrEqual($size);
    }


    /**
     * Check element(s) attribute value
     *
     * @param $value
     * @return Condition_Value
     */
    public static function value($value)
    {
        return new Condition_Value($value);
    }



    /**
     * Check strings equal
     *
     * @param $text
     * @return Condition_Text
     */
    public static function text($text)
    {
        return new Condition_Text($text);
    }


    /**
     * Check string contain text
     * @param $text
     * @return Condition_WithText
     */
    public static function withText($text)
    {
        return new Condition_WithText($text);
    }


    /**
     * Check dispayed
     *
     * @return Condition_Visible
     */
    public static function visible()
    {
        return new Condition_Visible(null);
    }


    /**
     * Check checkbox checked
     *
     * @return Condition_Checked
     */
    public static function checked()
    {
        return new Condition_Checked(null);
    }


    public static function attribute($attrName, $value)
    {
        return new Condition_Attribute($attrName, $value);
    }


    /**
     * Check child element exists
     *
     * @param By $locator
     * @return Condition_Child
     */
    public static function child(By $locator)
    {
        return new Condition_Child($locator);
    }


    /**
     * Check element text by regexp
     *
     * @param string $regExp
     * @return Condition_MatchText
     */
    public static function matchText($regExp)
    {
        return new Condition_MatchText($regExp);
    }

}
