<?php
namespace Selenide;

interface Condition_Interface_MatchCollection extends Condition_Interface_Match {
    public function matchCollectionPositive($collection);
    public function matchCollectionNegative($collection);
}
