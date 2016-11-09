<?php
namespace Selenide;

use Symfony\Component\Yaml\Tests\B;

class By
{

    protected $locator = '';


    /**
     * @param $name
     * @return By
     */
    public static function name($name)
    {
        return new static('name=' . $name);
    }


    /**
     * @param $text
     * @return By
     */
    public static function text($text)
    {
        //may be doesn't work
        return new static("xpath=//*[text()='{$text}']");
    }


    /**
     * @param $text
     * @return By
     */
    public static function withText($text)
    {
        return new static("xpath=//*[contains(text(), '{$text}')]");
    }


    /**
     * @param $css
     * @return By
     */
    public static function css($css)
    {
        return new static('css=' . $css);
    }


    /**
     * @param $xpath
     * @return By
     */
    public static function xpath($xpath)
    {
        return new static('xpath=' . $xpath);
    }


    /**
     * @param $tagName
     * @return By
     */
    public static function tagName($tagName)
    {
        return new static('tag=' . $tagName);
    }


    /**
     * @param $elementId
     * @return By
     */
    public static function id($elementId)
    {
        return new static('id=' . $elementId);
    }


    protected function __construct($locator)
    {
        $this->locator = $locator;
    }


    /**
     * @return string
     */
    public function asString()
    {
        return $this->locator;
    }


    public function __toString()
    {
        return $this->asString();
    }
}