<?php
namespace Selenide;

class Condition
{
    /**
     * @param $size
     * @return Condition_Size
     */
    public static function size($size)
    {
        return new Condition_Size($size);
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

}
