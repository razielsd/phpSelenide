<?php

namespace Selenide;

use PHPUnit\Framework\Assert;
use PHPUnit\Util\RegularExpression;

class Condition_MatchText extends Condition_Rule
    implements Condition_Interface_Match, Condition_Interface_assertCollection
{


    public function matchElement(\WebDriver_Element $element): bool
    {
        $actualText = $this->getActualValue($element);
        $state = RegularExpression::safeMatch($this->expected, $actualText);
        if ($state === false) {
            throw new Exception_ConditionMatchError('Match error with regexp: ' . $this->expected);
        }
        return (bool) $state;
    }


    public function assertCollectionPositive(array $elementList)
    {
        if (empty($elementList)) {
            throw new Exception_ElementNotFound('Elements not found for assertion');
        }
        foreach ($elementList as $index => $element) {
            $actualText = $this->getActualValue($element);
            $prefix = (count($elementList) > 1) ? ('Element[' . $index . ']: ') : '';
            Assert::assertRegExp(
                $this->expected,
                $actualText,
                $prefix . 'Text not matched regexp: ' . $this->expected . '. Actual: ' . $actualText
            );
        }
        return $this;
    }


    public function assertCollectionNegative(array $elementList)
    {
        if (empty($elementList)) {
            throw new Exception_ElementNotFound('Elements not found for assertion');
        }
        foreach ($elementList as $index => $element) {
            $actualText = $this->getActualValue($element);
            $prefix = (count($elementList) > 1) ? ('Element[' . $index . ']: ') : '';
            Assert::assertNotRegExp(
                $this->expected,
                $actualText,
                $prefix . 'Text  matched regexp: ' . $this->expected . '. Actual: ' . $actualText
            );
        }
        return $this;
    }


    protected function getActualValue(\WebDriver_Element $element)
    {
        return $element->text();
    }
}
