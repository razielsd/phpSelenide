<?php

require_once (__DIR__ . '/../lib/bootstrap.php');

use Selenide\By, Selenide\Condition, Selenide\Selenide;


class SelenideTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var Selenide
     */
    protected static $wd = null;
    /**
     * Url for test page in tests/www/webdrivertest.html
     * @var string
     */
    protected static $testUrl = 'http://devtest.dev/selenidehtml/';//'http://devtest.ru/tmp/webdrivertest.html';

    protected $backupStaticAttributesBlacklist = array(
        'SelenideTest' => array('wd')
    );


    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        self::$wd = new \Selenide\Selenide();

        self::$wd->connect();
        self::$wd->open(self::$testUrl);
    }


    public function testPressEnter()
    {
        self::$wd->find(By::id('e_textarea'))->setValue('Корыто')->pressEnter();
    }

    public function testSize()
    {
        self::$wd->findAll(By::css('#ires li.gtest'))
            ->shouldHave(Condition::size(10))
            ->shouldNotHave(Condition::size(9));
    }


    public function testText()
    {
        self::$wd->find(By::text('textOne'))
            ->shouldHave(Condition::text("textOne"))
            ->shouldNotHave(Condition::text("textTwo"));
    }


    public function testWithText()
    {
        self::$wd->find(By::withText('textTwo'))
            ->shouldHave(Condition::withText("textTwo"))
            ->shouldNotHave(Condition::withText("textOne"));
    }


    public function testTextSize()
    {
        self::$wd->find(By::id('childList'))
            ->findAll(By::tagName('li'))
            ->should(Condition::text('ChildTwo'))
            ->shouldHave(Condition::size(1));
    }


    public function testCollectionTextSize()
    {
        self::$wd->findAll(By::id('childList'))
            ->findAll(By::tagName('li'))
            ->should(Condition::text('ChildDouble'))
            ->shouldHave(Condition::size(2));
    }


    public function testCollectionWithTextSize()
    {
        self::$wd->findAll(By::id('childList'))
            ->findAll(By::tagName('li'))
            ->should(Condition::withText('ChildDouble'))
            ->shouldHave(Condition::size(2));
    }
}