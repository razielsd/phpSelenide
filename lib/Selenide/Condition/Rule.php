<?php
namespace Selenide;


abstract class Condition_Rule
{
    abstract protected function assert($element);
    abstract protected function assertNot($element);

    public function apply($element)
    {
        $this->assert($element);
    }


    public function applyNot($element)
    {
        $this->assertNot($element);
    }

}
