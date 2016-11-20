<?php
namespace Selenide;

use Symfony\Component\Yaml\Tests\B;

class By
{

    const TYPE_NAME = 'name';
    const TYPE_TEXT = 'text';
    const TYPE_WITH_TEXT = 'with_text';
    const TYPE_CSS = 'css';
    const TYPE_XPATH = 'xpath';
    const TYPE_TAG_NAME = 'tag_name';
    const TYPE_ID = 'id';
    const TYPE_TITLE = 'title';


    protected $type = null;
    protected $locator = '';


    /**
     * @param $name
     * @return By
     */
    public static function name($name)
    {
        return new static(self::TYPE_NAME, $name);
    }


    /**
     * @param $text
     * @return By
     */
    public static function text($text)
    {
        //may be doesn't work
        return new static(self::TYPE_TEXT, $text);
    }


    /**
     * @param $text
     * @return By
     */
    public static function withText($text)
    {
        return new static(self::TYPE_WITH_TEXT, $text);
    }


    /**
     * @param $css
     * @return By
     */
    public static function css($css)
    {
        return new static(self::TYPE_CSS, $css);
    }


    /**
     * @param $xpath
     * @return By
     */
    public static function xpath($xpath)
    {
        return new static(self::TYPE_XPATH, $xpath);
    }


    /**
     * @param $tagName
     * @return By
     */
    public static function tagName($tagName)
    {
        return new static(self::TYPE_TAG_NAME, $tagName);
    }


    /**
     * @param $elementId
     * @return By
     */
    public static function id($elementId)
    {
        return new static(self::TYPE_ID, $elementId);
    }


    /**
     * Search by title attribute
     *
     * @param $title
     * @return static
     */
    public static function title($title)
    {
        return new static(self::TYPE_TITLE, $title);
    }


    protected function __construct($type, $locator)
    {
        $this->type = $type;
        $this->locator = $locator;
    }


    /**
     * @return string
     */
    public function asString()
    {
        $locator = '';
        switch ($this->type) {
            case self::TYPE_NAME:
                $locator = 'name=' . $this->locator;
                break;
            case self::TYPE_TEXT:
                $locator = "xpath=//*[text()='" . $this->locator . "']";
                break;
            case self::TYPE_WITH_TEXT:
                $locator = "xpath=//*[contains(text(), '{$this->locator}')]";
                break;
            case self::TYPE_CSS:
                $locator = 'css=' . $this->locator;
                break;
            case self::TYPE_XPATH:
                $locator = 'xpath=' . $this->locator;
                break;
            case self::TYPE_ID:
                $locator = 'id=' . $this->locator;
                break;
            case self::TYPE_TAG_NAME:
                $locator = 'tag=' . $this->locator;
                break;
            case self::TYPE_TITLE:
                $locator = 'xpath=//*[@title="{$this->locator}"]';
                break;
        }
        return $locator;
    }


    public function getChildLocator()
    {
        $locator = '';
        switch ($this->type) {
            case self::TYPE_NAME:
                $locator = 'name=' . $this->locator;
                break;
            case self::TYPE_TEXT:
                $locator = "xpath=descendant:://*[text()='" . $this->locator . "']";
                break;
            case self::TYPE_WITH_TEXT:
                $locator = "xpath=descendant:://*[contains(text(), '{$this->locator}')]";
                break;
            case self::TYPE_CSS:
                $locator = 'css=' . $this->locator;
                break;
            case self::TYPE_XPATH:
                $locator = 'xpath=' . $this->locator;
                break;
            case self::TYPE_ID:
                $locator = 'id=' . $this->locator;
                break;
            case self::TYPE_TAG_NAME:
                $locator = 'tag=' . $this->locator;
                break;
            case self::TYPE_TITLE:
                $locator = 'xpath=descendant:://*[@title="{$this->locator}"]';
                break;
        }
        return $locator;
    }


    public function __toString()
    {
        return $this->asString();
    }
}