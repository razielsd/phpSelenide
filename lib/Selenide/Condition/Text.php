<?php

namespace Selenide;

use PHPUnit\Framework\Assert;

class Condition_Text extends Condition_Rule
    implements Condition_Interface_Match, Condition_Interface_assertCollection
{


    public function matchElement(\WebDriver_Element $element): bool
    {
        $actualText = $element->text();
        return $this->expected == $actualText;
    }


    public function assertCollectionPositive(array $elementList)
    {
        if (empty($elementList)) {
            throw new Exception_ElementNotFound('Elements not found for assertion');
        }
        foreach ($elementList as $index => $e) {
            $actualText = $e->text();
            $prefix = (count($elementList) > 1) ? ('Element[' . $index . ']: ') : '';
            Assert::assertEquals(
                $this->expected,
                $actualText,
                $prefix . 'Not found text: ' . $this->expected . '. Actual: ' . $actualText
            );
        }
        return $this;
    }


    public function assertCollectionNegative(array $elementList)
    {
        if (empty($elementList)) {
            throw new Exception_ElementNotFound('Elements not found for assertion');
        }
        foreach ($elementList as $index => $e) {
            $actualText = $e->text();
            $prefix = (count($elementList) > 1) ? ('Element[' . $index . ']: ') : '';
            Assert::assertNotEquals(
                $this->expected,
                $actualText,
                $prefix . 'Found text: ' . $this->expected . '. Actual: ' . $actualText
            );
        }
        return $this;
    }
}
