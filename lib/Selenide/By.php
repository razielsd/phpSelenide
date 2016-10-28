<?php
namespace Selenide;

class By
{

    public static function name($name)
    {
        return 'name=' . $name;
    }


    public static function text($text)
    {
        //may be doesn't work
        return "xpath=//*[text()='{$text}']";
    }

    public static function withText($text)
    {
        return "xpath=//*[contains(text(), '{$text}')]";
    }


    public static function css($css)
    {
        return 'css=' . $css;
    }


    public static function xpath($xpath)
    {
        return 'xpath=' . $xpath;
    }


    public static function tagName($tagName)
    {
        return 'tag=' . $tagName;
    }


    public static function id($elementId)
    {
        return 'id=' . $elementId;
    }

}