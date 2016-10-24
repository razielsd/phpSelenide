<?php
namespace Selenide;

class Condition_Size extends Condition_Rule
{
    protected $size = null;


    public function __construct($size)
    {
        $this->size = $size;
    }

    public function assert($element)
    {
        //check array
        $actualSize = count($element);
        if ($actualSize <> $this->size) {
            throw new Assertion('Size must be equal ' . $this->size . ', actual - ' . $actualSize);
        }
    }


    public function assertNot($element)
    {
        //check array
        $actualSize = count($element);
        if ($actualSize == $this->size) {
            throw new Assertion('Size must be NOT equal ' . $this->size . ', actual - ' . $actualSize);
        }
    }
}
