<?php

use Selenide\SelectorList;
use Selenide\Selector_Locator;
use Selenide\Selector_Condition;
use Selenide\By;
use Selenide\Condition;
use PHPUnit\Framework\TestCase;


class SelectorListTest extends TestCase
{
    public function testAccessAsArray()
    {
        $selectorList = new SelectorList();
        $dataList[] = new Selector_Locator(By::id('test'));
        $dataList[] = new Selector_Condition(Condition::size(1));
        foreach ($dataList as $selector) {
            $selectorList->add($selector);
        }
        foreach ($dataList as $index => $selector) {
            $this->assertSame($selector, $selectorList[$index]);
        }
    }


    public function testSetGet_GetSame() {
        $selectorList = new SelectorList();
        $dataList[] = new Selector_Locator(By::id('test'));
        $dataList[] = new Selector_Condition(Condition::size(1));
        foreach ($dataList as $selector) {
            $selectorList->add($selector);
        }
        foreach ($dataList as $index => $selector) {
            $this->assertSame($selector, $selectorList->get($index));
        }

    }


    public function testArrayIterator() {
        $dataList = $this->createDataList();
        $selectorList = $this->createSelectorList($dataList);
        $counter = 0;
        foreach ($selectorList as $selector) {
            ++$counter;
        }
        $this->assertGreaterThan(0, $counter, 'No selectors in selectorList');
        $this->assertEquals(count($dataList), $counter, 'Bad count selectors in selectorList');
    }


    public function testCount()
    {
        $dataList = $this->createDataList();
        $selectorList = $this->createSelectorList($dataList);
        $this->assertEquals(
            count($dataList),
            count($selectorList),
            'Elements count must be equals with dataList'
        );
    }


    protected function createDataList(): array
    {
        $dataList = [];
        $dataList[] = new Selector_Locator(By::id('test'));
        $dataList[] = new Selector_Condition(Condition::size(1));
        return $dataList;
    }


    protected function createSelectorList($dataList = []): SelectorList
    {
        $selectorList = new SelectorList();
        foreach ($dataList as $selector) {
            $selectorList->add($selector);
        }
        return $selectorList;
    }


}