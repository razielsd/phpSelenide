<?php
namespace Selenide;

interface Condition_Interface_ExpectedCollection {
    public function matchCollection(array $collection): bool;
}
