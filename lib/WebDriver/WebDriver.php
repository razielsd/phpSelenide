<?php

require_once __DIR__ . '/WebDriver/Http.php';
require_once __DIR__ . '/WebDriver/Config.php';
require_once __DIR__ . '/WebDriver/Driver.php';
require_once __DIR__ . '/WebDriver/Command.php';
require_once __DIR__ . '/WebDriver/Exception.php';
require_once __DIR__ . '/WebDriver/UtilException.php';
require_once __DIR__ . '/WebDriver/Exception/Protocol.php';
require_once __DIR__ . '/WebDriver/Exception/InvalidRequest.php';
require_once __DIR__ . '/WebDriver/Exception/FailedCommand.php';
require_once __DIR__ . '/WebDriver/Exception/StaleElementReference.php';
require_once __DIR__ . '/WebDriver/NoSeleniumException.php';
require_once __DIR__ . '/WebDriver/Object.php';
require_once __DIR__ . '/WebDriver/Object/Alert.php';
require_once __DIR__ . '/WebDriver/Object/Timeout.php';
require_once __DIR__ . '/WebDriver/Object/Cookie.php';
require_once __DIR__ . '/WebDriver/Object/Cookie/CookieInfo.php';
require_once __DIR__ . '/WebDriver/Object/Window.php';
require_once __DIR__ . '/WebDriver/Object/Frame.php';
require_once __DIR__ . '/WebDriver/Server/Selendroid.php';
require_once __DIR__ . '/WebDriver/Server/Appium.php';
require_once __DIR__ . '/WebDriver/Wait.php';
require_once __DIR__ . '/WebDriver/Element/Touch.php';
require_once __DIR__ . '/WebDriver/Touch.php';
require_once __DIR__ . '/WebDriver/Server/Selendroid/Touch.php';
require_once __DIR__ . '/WebDriver/Server/Selendroid/Touch/ActionBuilder.php';
require_once __DIR__ . '/WebDriver/Element.php';
require_once __DIR__ . '/WebDriver/Util.php';
require_once __DIR__ . '/WebDriver/Capabilities.php';
require_once __DIR__ . '/WebDriver/Capabilities/iOS.php';
require_once __DIR__ . '/WebDriver/Capabilities/Android.php';
require_once __DIR__ . '/WebDriver/Cache.php';

/**
 * Class WebDriver
 */
class WebDriver
{

    const ERROR_NO_SUCH_ELEMENT = 7;

    const DEFAULT_ELEMENT_CLASS = '\WebDriver_Element';

    const BUTTON_LEFT   = 0;
    const BUTTON_MIDDLE = 1;
    const BUTTON_RIGHT  = 2;

    const KEY_NULL       = 0xE000;
    const KEY_CANCEL     = 0xE001;
    const KEY_HELP       = 0xE002;
    const KEY_BACK_SPACE = 0xE003;
    const KEY_TAB        = 0xE004;
    const KEY_CLEAR      = 0xE005;
    const KEY_RETURN     = 0xE006;
    const KEY_ENTER      = 0xE007;
    const KEY_SHIFT      = 0xE008;
    const KEY_CONTROL    = 0xE009;
    const KEY_ALT        = 0xE00A;
    const KEY_PAUSE      = 0xE00B;
    const KEY_ESCAPE     = 0xE00C;
    const KEY_SPACE      = 0xE00D;
    const KEY_PAGE_UP    = 0xE00E;
    const KEY_PAGE_DOWN  = 0xE00F;
    const KEY_END        = 0xE010;
    const KEY_HOME       = 0xE011;
    const KEY_LEFT       = 0xE012;
    const KEY_UP         = 0xE013;
    const KEY_RIGHT      = 0xE014;
    const KEY_DOWN       = 0xE015;
    const KEY_INSERT     = 0xE016;
    const KEY_DELETE     = 0xE017;
    const KEY_SEMICOLON  = 0xE018;
    const KEY_EQUALS     = 0xE019;
    const KEY_NUM_0      = 0xE01A;
    const KEY_NUM_1      = 0xE01B;
    const KEY_NUM_2      = 0xE01C;
    const KEY_NUM_3      = 0xE01D;
    const KEY_NUM_4      = 0xE01E;
    const KEY_NUM_5      = 0xE01F;
    const KEY_NUM_6      = 0xE020;
    const KEY_NUM_7      = 0xE021;
    const KEY_NUM_8      = 0xE022;
    const KEY_NUM_9      = 0xE023;
    const KEY_MULTIPLY   = 0xE024;
    const KEY_ADD        = 0xE025;
    const KEY_SEPARATOR  = 0xE026;
    const KEY_SUBTRACT   = 0xE027;
    const KEY_DECIMAL    = 0xE028;
    const KEY_DIVIDE     = 0xE029;
    const KEY_F1         = 0xE031;
    const KEY_F2         = 0xE032;
    const KEY_F3         = 0xE033;
    const KEY_F4         = 0xE034;
    const KEY_F5         = 0xE035;
    const KEY_F6         = 0xE036;
    const KEY_F7         = 0xE037;
    const KEY_F8         = 0xE038;
    const KEY_F9         = 0xE039;
    const KEY_F10        = 0xE03A;
    const KEY_F11        = 0xE03B;
    const KEY_F12        = 0xE03C;
    const KEY_META       = 0xE03D;

    // server implementations
    const SERVER_SELENIUM = 'selenium';
    const SERVER_SELENDROID = 'selendroid';
    const SERVER_APPIUM = 'appium';

    // flick speed
    const SPEED_NORMAL = 0;
    const SPEED_FAST = 1;
    const SPEED_SLOW = 2;

    // Orientation
    const ORIENTATION_LANDSCAPE = 'LANDSCAPE';
    const ORIENTATION_PORTRAIT = 'PORTRAIT';

    /**
     * @var WebDriver_Driver
     */
    protected $driver = null;

    protected $objectList = [];

    /**
     * @var WebDriver_Config
     */
    protected $config = null;

    protected $elementClass = self::DEFAULT_ELEMENT_CLASS;

    /**
     * @var WebDriver_Cache|null
     */
    protected $cache = null;


    protected function __construct()
    {
        $this->config = new WebDriver_Config();
    }


    /**
     * @param string $serverType
     * @return WebDriver
     * @throws WebDriver_Exception
     */
    public static function factory($serverType = self::SERVER_SELENIUM)
    {
        switch ($serverType) {
            case self::SERVER_SELENIUM:
                return new static();
            case self::SERVER_APPIUM:
                return new WebDriver_Server_Appium();
            case self::SERVER_SELENDROID:
                return new WebDriver_Server_Selendroid();
        }

        throw new WebDriver_Exception("Invalid WebDriver server type: {$serverType}");
    }


    public function setElementClass($class)
    {
        if (!is_a((string) $class, '\WebDriver_Element', true)) {
            throw new WebDriver_Exception("Invalid WebDriver element class: {$class}");
        }
        $this->elementClass = (string) $class;
        return $this;
    }


    public function setDriver(WebDriver_Driver $driver)
    {
        $this->driver = $driver;
        return $this;
    }


    public function connect($sessionId = null)
    {
        $this->getDriver()->connect($sessionId);
        return $this;
    }


    public function getDriver()
    {
        if (null === $this->driver) {
            throw new WebDriver_Exception("No driver specified for WebDriver object");
        }
        return $this->driver;
    }


    /**
     * @return WebDriver_Config
     */
    public function config()
    {
        return $this->config;
    }


    /**
     * @return WebDriver_Object_Timeout
     */
    public function timeout()
    {
        if (!isset($this->objectList['timeout'])) {
            $this->objectList['timeout'] = new WebDriver_Object_Timeout($this->getDriver());
        }
        return $this->objectList['timeout'];
    }


    /**
     * @return WebDriver_Object_Alert
     */
    public function alert()
    {
        if (!isset($this->objectList['alert'])) {
            $this->objectList['alert'] = new WebDriver_Object_Alert($this->getDriver());
        }
        return $this->objectList['alert'];
    }


    /**
     * @return WebDriver_Object_Cookie
     */
    public function cookie()
    {
        if (!isset($this->objectList['cookie'])) {
            $this->objectList['cookie'] = new WebDriver_Object_Cookie($this->getDriver());
        }
        return $this->objectList['cookie'];
    }


    /**
     * @return WebDriver_Object_Window
     */
    public function window()
    {
        if (!isset($this->objectList['window'])) {
            $this->objectList['window'] = new WebDriver_Object_Window($this->getDriver());
        }
        return $this->objectList['window'];
    }


    /**
     * @return WebDriver_Object_Frame
     */
    public function frame()
    {
        return new WebDriver_Object_Frame($this->getDriver());
    }


    /**
     * Retrieve/Navigate the URL of the current page.
     *
     * @param null $url
     * @return mixed
     */
    public function url($url = null)
    {
        if ($url) {
            $command = $this->getDriver()->factoryCommand('url', WebDriver_Command::METHOD_POST, ['url' => $url]);
            //protect slowest pages and lost selenium connection
            try {
                $this->getDriver()->curl($command);
            } catch (WebDriver_NoSeleniumException $mainException) {
                if (!$this->config()->get(WebDriver_Config::IMPROVED_URL_OPEN)) {
                    throw $mainException;
                }
                try {
                    $currentUrl = $this->url();
                    $partCheck = [PHP_URL_PATH, PHP_URL_QUERY, PHP_URL_HOST];
                    $isEqual = true;
                    foreach ($partCheck as $urlPart) {
                        if (parse_url($url, $urlPart) != parse_url($currentUrl, $urlPart)) {
                            var_dump(parse_url($url, $urlPart));
                            $isEqual = false;
                        }
                    }
                    if (!$isEqual) {
                        throw $mainException;
                    }
                } catch (WebDriver_NoSeleniumException $e) {
                    throw $mainException;
                }
            }
        } else {
            $result = $this->getDriver()->curl(
                $this->getDriver()->factoryCommand('url', WebDriver_Command::METHOD_GET)
            );
            return $result['value'];
        }
    }


    /**
     * Navigate forwards in the browser history, if possible.
     */
    public function forward()
    {
        $this->getDriver()->curl($this->getDriver()->factoryCommand('forward', WebDriver_Command::METHOD_POST));
        return $this;
    }


    /**
     * Navigate backwards in the browser history, if possible.
     */
    public function back()
    {
        $this->getDriver()->curl($this->getDriver()->factoryCommand('back', WebDriver_Command::METHOD_POST));
        return $this;
    }


    /**
     * Refresh the current page.
     */
    public function refresh()
    {
        $this->getDriver()->curl($this->getDriver()->factoryCommand('refresh', WebDriver_Command::METHOD_POST));
        return $this;
    }


    /**
     * Inject a snippet of JavaScript into the page for execution in the context of the currently selected frame
     *
     * @param string $js
     * @param array $args
     * @return mixed
     */
    public function execute($js, $args = [])
    {
        $params = ['script' => $js, 'args' => $args];
        $result = $this->getDriver()->curl(
            $this->getDriver()->factoryCommand('execute', WebDriver_Command::METHOD_POST, $params)
        );
        return isset($result['value'])?$result['value']:false;
    }


    public function executeAsync($js, $args = [])
    {
        $params = ['script' => $js, 'args' => $args];
        $result = $this->getDriver()->curl(
            $this->getDriver()->factoryCommand('execute_async', WebDriver_Command::METHOD_POST, $params)
        );
        return $result['value'];
    }


    /**
     * Saves screenshot of current page to file $filename
     *
     * @param $filename
     * @return $this;
     */
    public function screenshot($filename)
    {
        file_put_contents($filename, $this->screenshotAsImage());
        return $this;
    }


    /**
     * Returns screenshot of current page as binary string.
     *
     * @return string;
     */
    public function screenshotAsImage()
    {
        $image = $this->getDriver()->curl(
            $this->getDriver()->factoryCommand('screenshot', WebDriver_Command::METHOD_GET)
        );
        return base64_decode($image['value']);
    }


    /**
     * Get page element using locator
     *
     * @param $locator
     * @return WebDriver_Element
     */
    public function find($locator)
    {
        $elementClass = $this->elementClass;
        return new $elementClass($this, $locator);
    }


    /**
     * Search for multiple elements on the page
     *
     * @param $locator
     * @param WebDriver_Element $parent
     *
     * @throws WebDriver_Exception
     *
     * @return WebDriver_Element[]
     */
    public function findAll($locator, $parent = null)
    {
        $commandUri = empty($parent) ? "elements" : "element/{$parent->getElementId()}/elements";
        $command = $this->getDriver()->factoryCommand(
            $commandUri,
            WebDriver_Command::METHOD_POST,
            WebDriver_Util::parseLocator($locator)
        );
        try {
            $elementList = $this->getDriver()->curl($command);
        } catch (WebDriver_Exception $e) {
            $parentText = empty($parent) ? '' : " (parent: {$parent->getLocator()})";
            throw new WebDriver_Exception("Elements not found: {$locator}{$parentText} with error: {$e->getMessage()}");
        }
        $result = [];
        foreach ($elementList['value'] as $value) {
            $elementClass = $this->elementClass;
            $result[] = new $elementClass($this, $locator, $parent, $value['ELEMENT']);
        }
        return $result;
    }


    /**
     * Click and hold the left mouse button (at the coordinates set by the last moveto command).
     *
     * @param $btn - const WebDriver::BUTTON_*
     * @return $this
     */
    public function buttonDown($btn)
    {
        $command = $this->getDriver()->factoryCommand('buttondown', WebDriver_Command::METHOD_POST, ['button' => $btn]);
        $this->getDriver()->curl($command);
        return $this;
    }


    /**
     * Releases the mouse button previously held (where the mouse is currently at).
     * Must be called once for every buttondown command issued.
     *
     * @param $btn
     * @return $this
     */
    public function buttonUp($btn)
    {
        $command = $this->getDriver()->factoryCommand('buttonup', WebDriver_Command::METHOD_POST, ['button' => $btn]);
        $this->getDriver()->curl($command);
        return $this;
    }


    /**
     * Sends keystroke sequence. Active modifiers are not cancelled after call, which lets to use them
     * with mouse events.
     *
     * @param array|int|string $charList
     * @return $this
     * @throws WebDriver_Exception
     */
    public function keys($charList)
    {
        $this->getDriver()->curl(
            $this->getDriver()->factoryCommand(
                'keys',
                WebDriver_Command::METHOD_POST,
                ['value' => WebDriver_Util::prepareKeyStrokes($charList)]
            )
        );
        return $this;
    }


    /**
     * @return WebDriver_Touch
     */
    public function touch()
    {
        return new WebDriver_Touch($this);
    }


    /**
     * Get the current page source.
     *
     * @return string
     */
    public function source()
    {
        $command = $this->getDriver()->factoryCommand('source', WebDriver_Command::METHOD_GET);
        $result = $this->getDriver()->curl($command);
        return $result['value'];
    }


    /**
     * Get the current page title.
     *
     * @return string
     */
    public function title()
    {
        $command = $this->getDriver()->factoryCommand('title', WebDriver_Command::METHOD_GET);
        $result = $this->getDriver()->curl($command);
        return $result['value'];
    }


    /**
     * Get current context.
     *
     * @return WebDriver_Cache
     */
    public function cache()
    {
        if (null === $this->cache) {
            $this->cache = new WebDriver_Cache($this);
        }
        return $this->cache;
    }


    /**
     * @param string $orientation
     * @return array
     * @throws WebDriver_Exception
     * @throws WebDriver_Exception_InvalidRequest
     * @throws WebDriver_NoSeleniumException
     */
    public function setScreenOrientation($orientation = self::ORIENTATION_LANDSCAPE)
    {
        if (!in_array($orientation, [self::ORIENTATION_LANDSCAPE, self::ORIENTATION_PORTRAIT])) {
            throw new WebDriver_Exception("Unknown orientation requested: {$orientation}");
        }

        return $this->getDriver()->curl(
            $this->getDriver()->factoryCommand(
                'orientation',
                \WebDriver_Command::METHOD_POST,
                ['orientation' => $orientation]
            )
        );
    }


    /**
     * Выполняет через Selendroid 'adb shell $command'
     *
     * @param string $command
     * @return array
     */
    public function executeAdbShellCommand($command)
    {
        return $this->getDriver()->curl(
            $this->getDriver()->factoryCommand(
                'selendroid/adb/executeShellCommand',
                \WebDriver_Command::METHOD_POST,
                ['command' => $command]
            )
        );
    }
}
