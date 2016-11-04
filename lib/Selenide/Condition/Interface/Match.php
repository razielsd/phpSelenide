<?php
namespace Selenide;

interface Condition_Interface_Match {
    public function matchElement(\WebDriver_Element $element): bool;
}
