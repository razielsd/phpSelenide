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
    protected static $testUrl = '/selenidehtml/';
    protected static $baseUrl = 'http://devtest.dev';

    protected $backupStaticAttributesBlacklist = array(
        'SelenideTest' => array('wd')
    );


    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        self::$wd = new \Selenide\Selenide();
        self::$wd->configuration()->baseUrl = self::$baseUrl;
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
            ->should(Condition::text("textOne"))
            ->shouldNot(Condition::text("textTwo"))
            ->shouldHave(Condition::text("textOne"))
            ->shouldNotHave(Condition::text("textTwo"));
    }


    public function testWithText()
    {
        self::$wd->find(By::withText('textTwo'))
            ->should(Condition::withText("textTwo"))
            ->shouldNot(Condition::withText("textOne"))
            ->shouldHave(Condition::withText("textTwo"))
            ->shouldNotHave(Condition::withText("textOne"));
    }


    public function testVisible()
    {
        self::$wd->find(By::withText('textTwo'))
            ->should(Condition::visible())
            ->shouldHave(Condition::visible());
    }


    public function testInvisible()
    {
        self::$wd->find(By::id('hidden-div'))
            ->shouldNot(Condition::visible())
            ->shouldNotHave(Condition::visible());
    }
}
