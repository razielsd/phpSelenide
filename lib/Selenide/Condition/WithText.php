<?php
namespace Selenide;

class Condition_WithText extends Condition_Rule
{
    protected $text = null;


    public function __construct($text)
    {
        $this->text = $text;
    }

    public function assert($element)
    {
        $showIndex = is_array($element);
        $elementList = [$element];
        foreach ($elementList as $index => $e) {
            $actualText = $e->text();
            if (mb_strpos($actualText, $this->text) === false) {
                $prefix = $showIndex ? ('Element[' . $index . ']: ') : '';
                throw new Assertion(
                    $prefix . 'Text must be contain ' . $this->text . ', actual - ' . $actualText
                );
            }
        }
    }


    public function assertNot($element)
    {
        $showIndex = is_array($element);
        $elementList = (array) $element;

        foreach ($elementList as $index => $e) {
            $actualText = $e->text();
            if (mb_strpos($actualText, $this->text) !== false) {
                $prefix = $showIndex ? ('Element[' . $index . ']: ') : '';
                throw new Assertion(
                    $prefix . 'Text must be NOT contain ' . $this->text . ', actual - ' . $actualText
                );
            }
        }
    }

}
