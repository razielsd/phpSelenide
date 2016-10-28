<?php
namespace Selenide;

class Condition_SizeGreaterThen extends Condition_Rule
{
    protected $size = null;


    public function __construct($size)
    {
        $this->size = $size;
    }

    protected function assert($element)
    {
        $actualSize = count($element);
        if ($actualSize <> $this->size) {
            throw new Assertion('Size must be equal ' . $this->size . ', actual - ' . $actualSize);
        }
    }


    protected function assertNegative($element)
    {
        $actualSize = count($element);
        if ($actualSize == $this->size) {
            throw new Assertion('Size must be NOT equal ' . $this->size . ', actual - ' . $actualSize);
        }
    }
}
