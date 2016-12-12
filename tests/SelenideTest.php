<?php

require_once (__DIR__ . '/../lib/bootstrap.php');

use Selenide\By, Selenide\Condition, Selenide\Selenide;


class SelenideTest extends PHPUnit_Framework_TestCase
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
    }


    public function setUp()
    {
        parent::setUp();
        self::$wd->getReport()->disable();
        self::$wd->configuration()->timeout = self::$timeout;
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


    public function testLength_ElementFound()
    {
        $this->assertEquals(
            1,
            self::$wd->find(By::id('e_textarea'))->length(),
            'Element must be exists'
        );
    }


    public function testLength_ElementNotFound()
    {
        $this->assertEquals(
            0,
            self::$wd->find(By::id('e_textareazz'))->length(),
            'Element must be not exists'
        );
        //self::$wd->findAll(By::css('#ires li.gtest'))
    }


    public function testLength_ElementListFound()
    {
        $this->assertEquals(
            10,
            self::$wd->findAll(By::css('#ires li.gtest'))->length(),
            'Element must be not exists'
        );
    }


    public function testPressEnter()
    {
        self::$wd->find(By::id('e_textarea'))->setValue('Корыто')->pressEnter();
    }


    /**
     * @expectedException Selenide\Exception_ElementNotFound
     */
    public function testElementNotFound()
    {
        self::$wd->description('Input text into not exists field')
            ->find(By::id('non_exists_input'))->setValue('Корыто')->pressEnter();
    }


    public function testExistsElementFound()
    {
        $this->assertTrue(
            self::$wd->find(By::id('e_textarea'))->exists(),
            'Element must be exists'
        );
    }


    public function testExistsElementNotFound()
    {
        self::$wd->configuration()->timeout = 1;
        $this->assertFalse(
            self::$wd->find(By::id('not_found_element'))->exists(),
            'Element must be not exists'
        );
    }


    public function testIsDisplayedElementFound()
    {
        $this->assertTrue(
            self::$wd->find(By::id('e_textarea'))->isDisplayed(),
            'Element must be displayed'
        );
    }


    public function testIsDisplayedElementNotFound()
    {
        self::$wd->configuration()->timeout = 1;
        $this->assertFalse(
            self::$wd->find(By::id('not_found_element'))->isDisplayed(),
            'Element must be not displayed'
        );
    }


    public function testIsDisplayedElementHidden()
    {
        $this->assertFalse(
            self::$wd->find(By::id('hidden-div'))->isDisplayed(),
            'Element must be not displayed'
        );
    }


    public function testSize()
    {
        self::$wd->findAll(By::css('#ires li.gtest'))
            ->should(Condition::size(10))
            ->shouldNot(Condition::size(9))
            ->assert(Condition::size(10))
            ->assertNot(Condition::size(9));
    }


    public function testSizeGreaterThen()
    {
        self::$wd->findAll(By::css('#ires li.gtest'))
            ->should(Condition::sizeGreaterThen(9))
            ->shouldNot(Condition::sizeGreaterThen(10))
            ->assert(Condition::sizeGreaterThen(9))
            ->assertNot(Condition::sizeGreaterThen(10));
    }


    public function testSizeGreaterThenOrEqual()
    {
        self::$wd->findAll(By::css('#ires li.gtest'))
            ->should(Condition::sizeGreaterThenOrEqual(10))
            ->shouldNot(Condition::sizeGreaterThenOrEqual(11))
            ->assert(Condition::sizeGreaterThenOrEqual(10))
            ->assertNot(Condition::sizeGreaterThenOrEqual(11));
    }


    public function testSizeLessThen()
    {
        self::$wd->findAll(By::css('#ires li.gtest'))
            ->should(Condition::sizeLessThen(11))
            ->shouldNot(Condition::sizeLessThen(10))
            ->assert(Condition::sizeLessThen(11))
            ->assertNot(Condition::sizeLessThen(10));
    }


    public function testSizeLessThenOrEqual()
    {
        self::$wd->findAll(By::css('#ires li.gtest'))
            ->should(Condition::sizeLessThenOrEqual(10))
            ->shouldNot(Condition::sizeLessThenOrEqual(9))
            ->assert(Condition::sizeLessThenOrEqual(10))
            ->assertNot(Condition::sizeLessThenOrEqual(9));
    }


    public function testConditionText()
    {
        self::$wd->find(By::text('textOne'))
            ->should(Condition::text("textOne"))
            ->shouldNot(Condition::text("textTwo"))
            ->assert(Condition::text("textOne"))
            ->assertNot(Condition::text("textTwo"));
    }


    /**
     * @expectedException Selenide\Exception_ElementNotFound
     */
    public function testConditionText_Empty()
    {
        self::$wd
            ->find(By::xpath("bad locator"))
            ->should(Condition::visible())
            ->assert(Condition::text("any text"));
    }


    public function testConditionTextCollection()
    {
        self::$wd->findAll(By::text('textOne'))
            ->should(Condition::text("textOne"))
            ->shouldNot(Condition::text("textTwo"))
            ->assert(Condition::text("textOne"))
            ->assertNot(Condition::text("textTwo"));
    }


    public function testConditionWithText()
    {
        self::$wd->find(By::withText('textTwo'))
            ->should(Condition::withText("textTwo"))
            ->shouldNot(Condition::withText("textOne"))
            ->assert(Condition::withText("textTwo"))
            ->assertNot(Condition::withText("textOne"));
    }


    public function testConditionWithTextCollection()
    {
        self::$wd->findAll(By::withText('textTwo'))
            ->should(Condition::withText("textTwo"))
            ->shouldNot(Condition::withText("textOne"))
            ->assert(Condition::withText("textTwo"))
            ->assertNot(Condition::withText("textOne"));
    }


    /**
     * @expectedException Selenide\Exception_ElementNotFound
     */
    public function testConditionWithText_Empty()
    {
        self::$wd
            ->find(By::xpath("bad locator"))
            ->should(Condition::visible())
            ->assert(Condition::withText("any text"));
    }


    public function testConditionVisible()
    {
        self::$wd->find(By::withText('textTwo'))
            ->should(Condition::visible())
            ->assert(Condition::visible());
    }


    /**
     * @expectedException Selenide\Exception_ElementNotFound
     */
    public function testConditionVisible_NotExistsElement()
    {
        self::$wd->find(By::id('not-exists-lement'))
            ->should(Condition::visible())
            ->assert(Condition::visible());
    }


    public function testConditionVisibleCollection()
    {
        self::$wd->findAll(By::withText('textTwo'))
            ->should(Condition::visible())
            ->assert(Condition::visible());
    }

    public function testDifficultText()
    {
        self::$wd->findAll(By::text('textThree'))
            ->should(Condition::visible())
            ->assert(Condition::visible());
    }


    public function testVisible_HiddenElement()
    {
        self::$wd->find(By::id('hidden-div'))
            ->shouldNot(Condition::visible())
            ->assertNot(Condition::visible());
    }


    public function testVisible_NotFoundElementAssertNot()
    {
            self::$wd->find(By::id('not-found-element'))
                ->assertNot(Condition::visible());
    }


    public function testInvisibleCollection()
    {
        self::$wd->findAll(By::id('hidden-div'))
            ->shouldNot(Condition::visible())
            ->assertNot(Condition::visible());
    }

    public function testConditionValue()
    {
        self::$wd->find(By::id("checkedBox"))
            ->should(Condition::value('1'))
            ->shouldNot(Condition::value('2'))
            ->assert(Condition::value('1'))
            ->assertNot(Condition::value('2'));
    }


    public function testConditionValueCollection()
    {
        self::$wd->findAll(By::tagName('input'))
            ->should(Condition::value('textValue'))
            ->shouldNot(Condition::value('textzzZ'))
            ->assert(Condition::value('textValue'))
            ->assertNot(Condition::value('textzzZ'));
    }


    /**
     * @expectedException Selenide\Exception_ElementNotFound
     */
    public function testConditionValue_Empty()
    {
        self::$wd
            ->find(By::xpath("bad locator"))
            ->assert(Condition::value("any text"));
    }


    public function testExistsElement()
    {
        $this->assertTrue(
            self::$wd->find(By::withText('textTwo'))
                ->should(Condition::withText("textTwo"))
                ->shouldNot(Condition::withText("textOne"))
                ->exists(),
            'Test element must be exists'
        );
    }


    public function testNotExistsElement()
    {
        $this->assertFalse(
            self::$wd->find(By::withText('NotExistedElement'))
                ->exists(),
            'Test element must be not exists'
        );
    }


    public function testExistsCollection()
    {
        $this->assertTrue(
            self::$wd->findAll(By::withText('textTwo'))
                ->should(Condition::withText("textTwo"))
                ->shouldNot(Condition::withText("textOne"))
                ->exists(),
            'Test elements must be exists'
        );
    }


    public function testNotExistsCollection()
    {
        $this->assertFalse(
            self::$wd->findAll(By::withText('NotExistsCollection'))
                ->exists(),
            'Test elements must be not exists'
        );
    }


    public function testDisplayedElement()
    {
        $this->assertTrue(
            self::$wd->find(By::withText('textTwo'))
                ->should(Condition::withText("textTwo"))
                ->shouldNot(Condition::withText("textOne"))
                ->isDisplayed(),
            'Test element must be displayed'
        );
    }


    public function testDisplayedCollection()
    {
        $this->assertTrue(
            self::$wd->findAll(By::withText('textTwo'))
                ->should(Condition::withText("textTwo"))
                ->shouldNot(Condition::withText("textOne"))
                ->isDisplayed(),
            'Test elements must be displayed'
        );
    }


    public function testClickElement()
    {

        self::$wd->findAll(By::withText('textTwo'))
            ->should(Condition::withText("textTwo"))
            ->shouldNot(Condition::withText("textOne"))
            ->click();
    }


    public function testClickCollection()
    {
        self::$wd->findAll(By::withText('textTwo'))
            ->should(Condition::withText("textTwo"))
            ->shouldNot(Condition::withText("textOne"))
            ->click();
    }


    public function testAttribute()
    {
        $attrValue = self::$wd->find(By::id('checkedBox'))
            ->attribute('id');
        $this->assertEquals('checkedBox', $attrValue, 'Attribute id must be is "checkbox"');
    }


    /**
     * @expectedException \Selenide\Exception_ElementNotFound
     */
    public function testNotExistsAttribute()
    {
        self::$wd->find(By::id('checkedBox'))
            ->attribute('unknown_attribute');
    }


    public function testConditionChecked()
    {
        self::$wd->find(By::xpath('//input[@type="checkbox"]'))
            ->should(Condition::checked())
            ->assert(Condition::checked());
    }


    public function testConditionNotChecked()
    {
        //failure
        self::$wd->findAll(By::xpath('//input[@type="checkbox"]'))
            ->shouldNot(Condition::checked())
            ->assertNot(Condition::checked());
    }


    public function testConditionAttribute()
    {
        self::$wd->findAll(By::xpath('//div[@data-attr-single]'))
            ->should(Condition::attribute('data-attribute', 'attribute-test'))
            ->shouldNot(Condition::attribute('data-attribute', 'none'))
            ->assert(Condition::size(1))
            ->assert(Condition::attribute('data-attribute', 'attribute-test'))
            ->assertNot(Condition::attribute('data-attribute', 'none'));
    }


    public function testConditionAttributeNotExists()
    {
        self::$wd->find(By::tagName('div'))
            ->should(Condition::attribute('no-attribute', 'attribute-test'))
            ->shouldNot(Condition::attribute('no-attribute', 'none'))
            ->assert(Condition::size(0));
    }


    public function testConditionAttributeCollection()
    {
        self::$wd->findAll(By::tagName('div'))
            ->should(Condition::attribute('data-attribute', 'attribute-test'))
            ->shouldNot(Condition::attribute('data-attribute', 'none'))
            ->assert(Condition::size(2))
            ->assert(Condition::attribute('data-attribute', 'attribute-test'))
            ->assertNot(Condition::attribute('data-attribute', 'none'));
    }


    public function testConditionAttributeCollectionNotExists()
    {
        self::$wd->findAll(By::tagName('div'))
            ->should(Condition::attribute('no-attribute', 'attribute-test'))
            ->shouldNot(Condition::attribute('no-attribute', 'none'))
            ->assert(Condition::size(0));
    }


    public function testConditionChild()
    {
        self::$wd->findAll(By::xpath('//div[@id="childTest"]/div'))
            ->should(Condition::child(By::xpath('div[contains(@data-test, "test01")]')))
            ->shouldNot(Condition::child(By::xpath('div[contains(@data-test, "test03")]')))
            ->assert(Condition::size(2))
            ->assert(Condition::child(By::xpath('div[contains(@data-test, "test01")]')))
            ->assertNot(Condition::child(By::xpath('div[contains(@data-test, "test03")]')));
    }


    public function testConditionMatchText()
    {
        self::$wd->find(By::id('regexptest'))
            ->should(Condition::matchText('/[0-9]+/'))
            ->shouldNot(Condition::matchText('/[z]+/'))
            ->assert(Condition::size(1))
            ->assert(Condition::matchText('/[0-9]+/'))
            ->assertNot(Condition::matchText('/[z]+/'));
    }


    public function testConditionRegExpNotFound()
    {
        self::$wd->find(By::id('regexptest'))
            ->should(Condition::matchText('/[z]+/'))
            ->shouldNot(Condition::matchText('/[0-9]+/'))
            ->assert(Condition::size(0));
    }


    /**
     * @expectedException \Selenide\Exception_ConditionMatchError
     */
    public function testConditionRegExpBadSyntax()
    {
        self::$wd->find(By::id('regexptest'))
            ->should(Condition::matchText('/[z]+'))
            ->shouldNot(Condition::matchText('/[0-9]+'))
            ->assert(Condition::size(0));
    }


    public function testChildSearch()
    {
        self::$wd->find(By::xpath('//div[@data-child="root"]'))
            ->find(By::xpath('//div[@data-child="child"]'))
            ->assert(Condition::size(1))
            ->assert(Condition::text('child'));
    }


    public function testFindAllChildren()
    {
        self::$wd
            ->findAll(By::xpath('div[@data-name="find-all-child"]'))
            ->assert(Condition::size(0));
    }


    public function testFindAllParent()
    {
        self::$wd
            ->findAll(By::xpath('//div[@data-name="find-all"]'))
            ->findAll(By::xpath('div[@data-name="find-all"]'))
            ->assert(Condition::size(0));
    }


    public function testFindAllChild()
    {
        self::$wd
            ->findAll(By::xpath('//div[@data-name="find-all"]'))
            ->findAll(By::xpath('div[@data-name="find-all-child"]'))
            ->assert(Condition::size(6))
            ->assert(Condition::withText('find-all-child-0'));
    }


    public function testFindAllDescedant()
    {
        self::$wd
            ->findAll(By::xpath('//div[@data-name="find-all"]'))
            ->findAll(By::xpath('descendant::div[@data-name="find-all-child"]'))
            ->assert(Condition::size(12))
            ->assert(Condition::withText('find-all-child-0'));
    }


    public function testText_Element_ReturnType()
    {
        $text = self::$wd->find(By::text('textOne'))
            ->text();
        $this->assertInternalType('string', $text, 'String expected for first element');
    }


    public function testText_Element_DescrReturnType()
    {
        $text = self::$wd->find(By::text('textOne'))
            ->description('Get text for test')
            ->text();
        $this->assertInternalType('string', $text, 'String expected for first element');
    }


    public function testText_Collection_ReturnType()
    {
        $text = self::$wd->findAll(By::text('textOne'))
            ->should(Condition::text("textOne"))
            ->text();
        $this->assertInternalType('array', $text, 'Array expected for collection');

    }


    public function testAttribute_Element_ReturnType()
    {
        $attrValue = self::$wd->find(By::id('checkedBox'))
            ->attribute('id');
        $this->assertInternalType('string', $attrValue, 'String expected for first element');
    }


    public function testAttribute_Collection_ReturnType()
    {
        $attrValue = self::$wd->findAll(By::id('checkedBox'))
            ->attribute('id');
        $this->assertInternalType('array', $attrValue, 'Array expected for collection');

    }


    public function testVal_Element_ReturnType()
    {
        $value = self::$wd->find(By::tagName('input'))
            ->val();
        $this->assertInternalType('string', $value, 'String expected for first element');
    }


    public function testVal_Collection_ReturnType()
    {
        $value = self::$wd->findAll(By::tagName('input'))
            ->val();
        $this->assertInternalType('array', $value, 'Array expected for collection');
    }


    public function testConditionExists_ElementExists()
    {
        self::$wd->find(By::id('hidden-div'))
            ->should(Condition::exists())
            ->assert(Condition::exists());
    }


    public function testConditionExists_ElementNotExists_Failed()
    {
        self::$wd->find(By::id('not-exists-element'))
            ->shouldNot(Condition::exists())
            ->assertNot(Condition::exists());

    }


    public function testGet_ByIndex()
    {
        $element = self::$wd->findAll(By::tagName('input'))
            ->get();
        $this->assertInstanceOf('Selenide\SelenideElement', $element, 'Must be return SelenideElement');
    }


    public function testExecute_Js_Correct()
    {
        $this->assertEquals(
            8,
            self::$wd->execute('return 3+5;'),
            'js must be return number 8'
        );
    }


    public function testExecute_Js_SingleElement()
    {
        $element = self::$wd->find(By::id('testJs'));
        $element->assert(Condition::size(1));
        $this->assertEquals(
            'javascript',
            $element->execute('return arguments[0].innerHTML;'),
            'js must be return text "javascript"'
        );
    }


    public function testCountable_Collection_DynamicSelectors()
    {
        $collection = self::$wd->findAll(By::css('.collection-element'));
        $this->assertCount(5, $collection, 'Size of collection must be 5');
        $collection->should(Condition::attribute('data-pew', 'exclusive'));
        $this->assertCount(1, $collection, 'Size of collection must be 1');
    }


    public function testArrayAccess_Collection_Basic()
    {
        $collection = self::$wd->findAll(By::css('.collection-element'));

        $this->assertEquals('0', $collection[0]->text(), 'Text in element must be "0"');
        $this->assertEquals('1', $collection[1]->text(), 'Text in element must be "1"');
        $this->assertEquals('2', $collection[2]->text(), 'Text in element must be "2"');
        $this->assertEquals('3', $collection[3]->text(), 'Text in element must be "3"');
        $this->assertEquals('4', $collection[4]->text(), 'Text in element must be "4"');

        $this->assertFalse(isset($collection[5]), 'Element with index 5 must not be exist');
    }


    public function testArrayAccess_Collection_Foreach()
    {
        $collection = self::$wd->findAll(By::css('.collection-element'));
        $result = '';
        foreach ($collection as $element) {
            $result .= $element->text();
        }
        $this->assertEquals('01234', $result, 'Concatenated text must be "01234"');
    }


    public function testIframe_Focus_Basic()
    {
        self::$wd
            ->description('Should not find text in iframe')
            ->find(By::name('testframe'))
            ->assert(Condition::visible())
            ->assertNot(Condition::withText('frame'));

        self::$wd
            ->focus(By::name('testframe'))
            ->description('Searches text in iframe')
            ->find(By::id('frameTxt'))
            ->assert(Condition::visible())
            ->assert(Condition::text('frame'));
    }


    public function testIframe_Focus_Return()
    {
        self::$wd
            ->description('Should find div on main page')
            ->find(By::id('lower_element'))
            ->assert(Condition::exists());
        self::$wd
            ->focus(By::name('testframe'))
            ->description('Focus on iframe');
        self::$wd
            ->description('Should not find div in iframe')
            ->find(By::id('lower_element'))
            ->assertNot(Condition::exists());
        self::$wd
            ->focus()
            ->description('Focus on main page');
        self::$wd
            ->description('Should find div on main page')
            ->find(By::id('lower_element'))
            ->assert(Condition::exists());
    }


    public function testSource_Get()
    {
        $collection = self::$wd->find(By::id('elementSourceTest'));
        $this->assertEquals(
            '<div id="elementSourceTest">Hello!</div>',
            $collection->source(),
            'Got wrong element html'
        );
    }
}
