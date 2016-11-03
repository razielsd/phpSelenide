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
     * @return Condition_Size
     */
    public static function sizeGreaterThen($size)
    {
        return new Condition_SizeGreaterThen($size);
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


}
