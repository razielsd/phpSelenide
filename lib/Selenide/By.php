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
                $locator =
                    'xpath=//*[normalize-space(.)=' . $this->escapeString($this->locator) .
                    ' and not(.//*[normalize-space(.)=' . $this->escapeString($this->locator) . '])]';
                break;
            case self::TYPE_WITH_TEXT:
                $locator =
                    'xpath=//*[contains(normalize-space(.), ' . $this->escapeString($this->locator) .
                    ') and not(.//*[contains(normalize-space(.), ' . $this->escapeString($this->locator) . ')])]';
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
                $locator = 'xpath=//*[@title=' . $this->escapeString($this->locator) . ']';
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
                $locator =
                    'xpath=descendant::*[normalize-space(.)=' . $this->escapeString($this->locator) .
                    ' and not(.//*[normalize-space(.)=' . $this->escapeString($this->locator) . '])]';
                break;
            case self::TYPE_WITH_TEXT:
                $locator =
                    'xpath=descendant::*[contains(normalize-space(.), ' . $this->escapeString($this->locator) .
                    ') and not(.//*[contains(normalize-space(.), ' . $this->escapeString($this->locator) . ')])]';
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
                $locator = 'xpath=descendant::*[@title=' . $this->escapeString($this->locator) . ']';
                break;
        }
        return $locator;
    }


    public function __toString()
    {
        return $this->asString();
    }


    /**
     * Convert strings with both quotes and ticks into a valid xpath component
     *
     * For example,
     *
     * <p>
     *   {@code foo} will be converted to {@code "foo"},
     * </p>
     * <p>
     *   {@code f"oo} will be converted to {@code 'f"oo'},
     * </p>
     * <p>
     *   {@code foo'"bar} will be converted to {@code concat("foo'", '"', "bar")}
     * </p>
     *
     * @param string $toEscape a text to escape quotes in, e.g. {@code "f'oo"}
     * @return string the same text with escaped quoted, e.g. {@code "\"f'oo\""}
     */
    public static function escapeString(string $toEscape): string
    {
        if (mb_strpos($toEscape, '"') !== false && mb_strpos($toEscape, "'") !== false) {
            $substringsWithoutDoubleQuotes = explode('"', $toEscape);

            $quoted = [];
            foreach ($substringsWithoutDoubleQuotes as $key => $substring) {
                $quoted[] = '"' . $substring . '"';
                if ($key == count($substringsWithoutDoubleQuotes) - 1) {
                    break;
                }
                $quoted[] = "'\"'";
            }
            return 'concat(' . implode(", ", $quoted) . ')';
        }

        // Escape string with just a quote into being single quoted: f"oo -> 'f"oo'
        if (mb_strpos($toEscape, '"') !== false) {
            return "'" . $toEscape . "'";
        }

        // Otherwise return the quoted string
        return '"' . $toEscape . '"';
    }
    
}