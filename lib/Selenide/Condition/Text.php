<?php
namespace Selenide;

class Condition_Text extends Condition_Rule
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
            if ($this->text != $actualText) {
                $prefix = $showIndex ? ('Element[' . $index . ']: ') : '';
                throw new Assertion(
                    $prefix . 'Text must be equal ' . $this->text . ', actual - ' . $actualText
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
            if ($this->text == $actualText) {
                $prefix = $showIndex ? ('Element[' . $index . ']: ') : '';
                throw new Assertion(
                    $prefix . 'Text must be NOT equal ' . $this->text . ', actual - ' . $actualText
                );
            }
        }
    }

}
