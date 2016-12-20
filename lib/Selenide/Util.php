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
