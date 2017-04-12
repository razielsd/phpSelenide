<?php

require_once (__DIR__ . '/../lib/bootstrap.php');
require_once (__DIR__ . '/lib/TestListener.php');

use PHPUnit\Framework\TestCase;
use Selenide\By, Selenide\Condition, Selenide\Selenide;

class ListenerTest extends TestCase
{

    /**
     * @var Selenide
     */
    protected static $wd = null;
    protected static $timeout = 5;
    /**
     * Url for test page in tests/www/webdrivertest.html
     * @var string
     */
    protected static $testUrl = '/';
    protected static $baseUrl = 'http://127.0.0.1:8000';
    protected static $config = null;

    protected $backupStaticAttributesBlacklist = array(
        'SelenideTest' => array('wd'),
    );


    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        self::$wd = new \Selenide\Selenide();
        self::$wd->configuration()->baseUrl = self::config('selenium/baseUrl');
        self::$wd->configuration()->host = self::config('selenium/host');
        self::$wd->configuration()->port = self::config('selenium/port');
        self::$wd->connect();
        self::$wd->getDriver()->webDriver()->timeout()->implicitWait(1);
        self::$wd->open(self::$testUrl);

        $listener = new TestListener();
        self::$wd->addListener($listener);
    }


    public function setUp()
    {
        parent::setUp();
        self::$wd->getReport()->disable();
        self::$wd->configuration()->timeout = self::$timeout;
        TestListener::clean();
    }


    protected static function config($path)
    {
        if (self::$config === null) {
            $configPath = __DIR__ . '/etc/config.php';
            $devPath = __DIR__ . '/etc/config.dev.php';
            $config = include ($configPath);
            if (file_exists($devPath)) {
                //only top level group replace
                $devCfg = include ($devPath);
                foreach ($devCfg as $name => $opts) {
                    $config[$name] = $opts;
                }
            }
            self::$config = $config;
        }
        $path = explode('/', trim($path, '/'));
        $value = self::$config;
        foreach ($path as $nodeName) {
            $value = $value[$nodeName];
        }
        return $value;
    }


    public function testListener_Click_CallListener()
    {
        $description = __METHOD__;
        self::$wd
            ->description($description)
            ->find(By::withText('textTwo'))
            ->should(Condition::withText("textTwo"))
            ->shouldNot(Condition::withText("textOne"))
            ->click();

        $this->assertCount(2, TestListener::$data, 'No data in listener');
        $event = TestListener::$data[1];
        $this->assertArrayHasKey('method', $event, 'No data about method');
        $this->assertArrayHasKey('element', $event, 'No data about element');
        $this->assertArrayHasKey('locator', $event, 'No data about locator');
        $this->assertArrayHasKey('description', $event, 'No data about description');

        $this->assertEquals('beforeClick', $event['method'], 'Must be called Listener::beforeClick');
        $this->assertInstanceOf('Selenide\SelenideElement', $event['element'], 'Element must be is SelenideElement');
        $this->assertGreaterThan(10, strlen($event['locator']), 'Locator too short');
        $this->assertEquals(
            $description, $event['description'], 'Description must equals is selenide->description()'
        );
    }


    public function testListener_SetValue_CallListener()
    {
        $description = __METHOD__;
        $textValue = 'washtub';
        self::$wd
            ->description($description)
            ->find(By::id('e_textarea'))
            ->setValue($textValue);
        $this->assertCount(2, TestListener::$data, 'No data in listener');
        $event = TestListener::$data[1];
        $this->assertArrayHasKey('method', $event, 'No data about method');
        $this->assertArrayHasKey('element', $event, 'No data about element');
        $this->assertArrayHasKey('locator', $event, 'No data about locator');
        $this->assertArrayHasKey('value', $event, 'No data about value');

        $this->assertArrayHasKey('description', $event, 'No data about description');
        $this->assertEquals('beforeSetValue', $event['method'], 'Must be called Listener::beforeClick');
        $this->assertEquals($textValue, $event['value'], 'Bad value of setValue');
        $this->assertInstanceOf('Selenide\SelenideElement', $event['element'], 'Element must be is SelenideElement');
        $this->assertGreaterThan(10, strlen($event['locator']), 'Locator too short');
        $this->assertEquals(
            $description, $event['description'], 'Description must equals is selenide->description()'
        );
    }


    public function testListener_Assert_CallListener()
    {
        $description = __METHOD__;
        self::$wd
            ->description($description)
            ->find(By::withText('textTwo'))
            ->assert(Condition::visible());

        $this->assertCount(2, TestListener::$data, 'No data in listener');
        $event = TestListener::$data[1];
        $this->assertArrayHasKey('method', $event, 'No data about method');
        $this->assertArrayHasKey('locator', $event, 'No data about locator');
        $this->assertArrayHasKey('condition', $event, 'No data about condition');

        $this->assertEquals('beforeAssert', $event['method'], 'Must be called Listener::beforeAssert');
        $this->assertInstanceOf(
            'Selenide\Condition_Rule', $event['condition'], 'Condition must be is Selenide\Condition_Rule'
        );
        $this->assertGreaterThan(10, strlen($event['locator']), 'Locator too short');
    }


    public function testListener_AssertNot_CallListener()
    {
        $description = __METHOD__;
        self::$wd
            ->description($description)
            ->find(By::withText('textTwo'))
            ->assertNot(Condition::size(20));

        $this->assertCount(2, TestListener::$data, 'No data in listener');
        $event = TestListener::$data[1];
        $this->assertArrayHasKey('method', $event, 'No data about method');
        $this->assertArrayHasKey('locator', $event, 'No data about locator');
        $this->assertArrayHasKey('condition', $event, 'No data about condition');

        $this->assertEquals('beforeAssertNot', $event['method'], 'Must be called Listener::beforeAssertNot');
        $this->assertInstanceOf(
            'Selenide\Condition_Rule', $event['condition'], 'Condition must be is Selenide\Condition_Rule'
        );
        $this->assertGreaterThan(10, strlen($event['locator']), 'Locator too short');
    }
}
