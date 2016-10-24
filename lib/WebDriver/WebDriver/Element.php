<?php

class WebDriver_Element
{
    const TYPE_FIELD_TEXT = 'field_text';
    const TYPE_FIELD_TEXT_AREA = 'field_textarea';
    const TYPE_FIELD_SELECT = 'field_select';
    const TYPE_FIELD_FILE = 'field_file';
    const TYPE_NODE = 'field_node';


    /**
     * @var WebDriver_Driver
     */
    protected $driver = null;
    protected $locator = null;
    protected $elementId = null;
    /**
     * @var null|WebDriver_Element
     */
    protected $parent = null;
    protected $presentTimeout = 1;
    protected $waitTimeout = 30;
    /**
     * @var WebDriver
     */
    protected $webDriver = null;
    protected $description = null;

    /**
     * @var resource|null
     */
    protected $image = null;


    /**
     * Last button pressed down, saved in self::buttonDown
     */
    protected $state = [
        'buttonDown' => null,
        'tagName' => null,
        'size' => null,
    ];


    /**
     * @param WebDriver $webDriver
     * @param $locator
     * @param WebDriver_Element $parent
     * @param null $elementId
     */
    public function __construct(WebDriver $webDriver, $locator, WebDriver_Element $parent = null, $elementId = null)
    {
        $this->webDriver = $webDriver;
        $this->driver = $webDriver->getDriver();
        $this->locator = $locator;
        $this->parent = $parent;
        $this->elementId = $elementId;
    }


    public function __destruct()
    {
        if (null !== $this->image && is_resource($this->image)) {
            imagedestroy($this->image);
        }
    }


    public function __clone()
    {
        if (null !== $this->image) {
            $this->image = null;
        }
    }


    /**
     * Get element Id from webdriver
     *
     * @return int
     * @throws WebDriver_Exception
     */
    public function getElementId()
    {
        if ($this->elementId === null) {
            $param = WebDriver_Util::parseLocator($this->locator);
            $commandUri = (null === $this->parent) ? 'element' : "element/{$this->parent->getElementId()}/element";
            $command = $this->driver->factoryCommand($commandUri, WebDriver_Command::METHOD_POST, $param);
            try {
                $result = $this->driver->curl($command);
            } catch (WebDriver_Exception $e) {
                $parentText = (null === $this->parent) ? '' : " (parent: {$this->parent->getLocator()})";
                $e->setMessage("Element not found: {$this->locator}{$parentText}  with error: {$e->getMessage()}");
                throw $e;
            }
            if (!isset($result['value']['ELEMENT'])) {
                $parentText = (null === $this->parent) ? '' : " (parent: {$this->parent->getLocator()})";
                throw new WebDriver_Exception("Element not found: {$this->locator}{$parentText}");
            }
            $this->elementId = $result['value']['ELEMENT'];
        }
        return $this->elementId;
    }


    /**
     * Builds reference to object for using as argument in WebDriver::execute().
     *
     * @return array
     */
    public function getReference()
    {
        return ['ELEMENT' => (string) $this->getElementId()];
    }


    /**
     * Use for set/get info about element in your application
     *
     * @param string|null $descr
     * @return WebDriver_Element|string
     */
    public function description($descr = null)
    {
        if ($descr === null) {
            return $this->description;
        } else {
            $this->description = $descr;
            return $this;
        }
    }


    /**
     * Get element locator used for __constructor
     *
     * @return string
     */
    public function getLocator()
    {
        return $this->locator;
    }


    /**
     * Refresh element data, doesn't work for element from findAll/childAll
     */
    public function refresh()
    {
        $this->elementId = null;
        return $this;
    }


    protected function sendCommand($command, $method, $params = array(), $errorMessage = '')
    {
        try {
            $command = $this->driver->factoryCommand($command, $method, $params)
                ->param(['id' => $this->getElementId()]);
            return $this->driver->curl($command);
        } catch (WebDriver_Exception $e) {
            $e->setMessage("{$e->getMessage()}\nLocator: {$this->getLocator()}");
            throw $e;
        } catch (Exception $e) {
            $errorMessage = empty($errorMessage)?'':"\n";
            $errorMessage .= 'Locator: ' . $this->getLocator();
            $refObject   = new ReflectionObject($e);
            $refProperty = $refObject->getProperty('message');
            $refProperty->setAccessible(true);
            $refProperty->setValue($e, $errorMessage . "\n" . $e->getMessage());
            throw $e;
        }
    }


    /**
     * @param string $errorMessage
     * @return WebDriver_Element
     */
    public function click($errorMessage = '')
    {
        $this->sendCommand('element/:id/click', WebDriver_Command::METHOD_POST, [], $errorMessage);
        return $this;
    }


    /**
     * @param int $xoffset
     * @param int $yoffset
     * @param WebDriver_Element $element
     * @return WebDriver_Element
     */
    public function moveto($xoffset = null, $yoffset = null, WebDriver_Element $element = null)
    {
        $element = ($element)?$element:$this;
        $params = [
            'element' => "{$element->getElementId()}"
        ];
        if ($xoffset !== null) {
            $params['xoffset'] = intval($xoffset);
            $params['yoffset'] = intval($yoffset);
        }
        $this->sendCommand('moveto', WebDriver_Command::METHOD_POST, $params);
        return $this;
    }


    /**
     * @param int $btn
     * @return WebDriver_Element
     */
    public function buttonDown($btn)
    {
        $this->moveto();
        $this->webDriver->buttonDown($btn);
        $this->state['buttonDown'] = $btn;
        return $this;
    }


    /**
     * @param int $btn
     * @return WebDriver_Element
     */
    public function buttonUp($btn = null)
    {
        $btn = ($btn)?$btn:$this->state['buttonDown'];
        $this->webDriver->buttonUp($btn);
        $this->state['buttonDown'] = null;
        return $this;
    }


    /**
     * @param int $xOffset
     * @param int $yOffset
     * @param WebDriver_Element $element
     * @return WebDriver_Element
     */
    public function dragAndDrop($xOffset, $yOffset, WebDriver_Element $element = null)
    {
        $size = $this->size();
        if (null !== $xOffset) {
            $xOffset = (int) round($xOffset + $size['width'] / 2);
        }
        if (null !== $yOffset) {
            $yOffset = (int) round($yOffset + $size['height'] / 2);
        }
        return $this
            ->buttonDown(WebDriver::BUTTON_LEFT)
            ->moveto($xOffset, $yOffset, $element)
            ->buttonUp();
    }


    /**
     * Submit form element
     *
     * @return null
     */
    public function submit()
    {
        $this->sendCommand('element/:id/submit', WebDriver_Command::METHOD_POST);
    }


    public function text()
    {
        $result = $this->sendCommand('element/:id/text', WebDriver_Command::METHOD_GET);
        return $result['value'];
    }


    /**
     * Get element type (text input, select and etc)
     */
    public function getType()
    {
        $map = [
            'input' => self::TYPE_FIELD_TEXT,
            'textarea' => self::TYPE_FIELD_TEXT_AREA,
            'select' => self::TYPE_FIELD_SELECT,
        ];
        $tagName = strtolower($this->tagName());
        $typeField = isset($map[$tagName]) ? $map[$tagName] : self::TYPE_NODE;
        if (($typeField == self::TYPE_FIELD_TEXT) && (strtolower($this->attribute('type')) == 'file')) {
            $typeField = self::TYPE_FIELD_FILE;
        }
        return $typeField;
    }


    /**
     * Set element value
     *
     * @param $value
     *
     * @return WebDriver_Element|string
     */
    public function value($value = null)
    {
        $fieldType = $this->getType();
        if ($value !== null) {
            switch ($fieldType) {
                case self::TYPE_FIELD_TEXT:
                case self::TYPE_FIELD_FILE:
                case self::TYPE_FIELD_TEXT_AREA:
                    if ($fieldType != self::TYPE_FIELD_FILE) {
                        $this->clear();
                    }
                    $params = ['value' => ["{$value}"]];
                    $this->sendCommand('element/:id/value', WebDriver_Command::METHOD_POST, $params);
                    break;
                case self::TYPE_FIELD_SELECT:
                    $this->waitPresent()->child(sprintf("xpath=descendant::option[@value='%s']", $value))
                        ->waitPresent()
                        ->click();
                    break;
            }
            return $this;
        } else {
            $value = null;
            switch ($fieldType) {
                case self::TYPE_FIELD_TEXT:
                case self::TYPE_FIELD_FILE:
                case self::TYPE_FIELD_TEXT_AREA:
                case self::TYPE_FIELD_SELECT:
                    $value = $this->attribute('value');
                    break;
                default:
                    $value = $this->text();
                    if ($this->webDriver->config()->get(WebDriver_Config::TRIM_TEXT_NODE_VALUE)) {
                        $value = trim($value);
                    }
            }
            return $value;
        }
    }


    /**
     * Clear textarea/input field, for select field choose first option
     *
     * @return WebDriver_Element
     */
    public function clear()
    {
        $tagName = $this->tagName();
        switch ($tagName) {
            case 'input':
            case 'textarea':
                $this->sendCommand('element/:id/clear', WebDriver_Command::METHOD_POST);
                break;
            case 'select':
                $this->child("xpath=descendant::option[1]")->click();
                break;
        }
        return $this;
    }


    public function keys($charList)
    {
        $this->sendCommand(
            'element/:id/value',
            WebDriver_Command::METHOD_POST,
            ['value' => WebDriver_Util::prepareKeyStrokes($charList)]
        );
        return $this;
    }


    /**
     * Get element tag name
     *
     * @return mixed
     */
    public function tagName()
    {
        if (!$this->state['tagName']) {
            $result = $this->sendCommand('element/:id/name', WebDriver_Command::METHOD_GET);
            $this->state['tagName'] = strtolower($result['value']);
        }
        return $this->state['tagName'];
    }


    /**
     * Get element attribute value
     *
     * @param $attrName
     * @return string
     */
    public function attribute($attrName)
    {
        $result = $this->sendCommand('element/:id/attribute/' . $attrName, WebDriver_Command::METHOD_GET);
        return $result['value'];
    }


    /**
     * Search for an element on the page, starting from the current element.
     *
     * @param $locator
     * @return WebDriver_Element
     */
    public function child($locator)
    {
        return new static($this->webDriver, $locator, $this);
    }


    /**
     * Search for multiple elements on the page, starting from the current element
     *
     * @param $locator
     * @return WebDriver_Element[]
     */
    public function childAll($locator)
    {
        return $this->webDriver->findAll($locator, $this);
    }


    /**
     * @return bool
     */
    public function enabled()
    {
        $result = $this->sendCommand('element/:id/enabled', WebDriver_Command::METHOD_GET);
        return (bool) $result['value'];
    }


    /**
     * Get state for checkbox elements
     *
     * @return bool
     */
    public function checked()
    {
        return ('true' == $this->attribute('checked'));
    }


    public function size()
    {
        $result = $this->sendCommand('element/:id/size', WebDriver_Command::METHOD_GET);
        $value = $result['value'];
        return ['width' => $value['width'], 'height' => $value['height']];
    }


    /**
     * Get element upper-left corner of the page
     */
    public function location()
    {
        $result = $this->sendCommand('element/:id/location', WebDriver_Command::METHOD_GET);
        $value = $result['value'];
        return ['x' => $value['x'], 'y' => $value['y']];
    }


    public function isPresent()
    {
        $result = true;
        try {
            $this->webDriver->timeout()->implicitWait($this->presentTimeout * 1000);
            $this->getElementId();
        } catch (WebDriver_UtilException $e) {
            throw $e;
        } catch (Exception $e) {
            // TODO: Отрефакторить нормально ситуацию с исключениями.
            $result = false;
        } finally {
            $this->restoreImplicitWait();
        }
        return $result;
    }


    protected function restoreImplicitWait()
    {
        $this->webDriver->timeout()->implicitWait($this->waitTimeout * 1000);
        return $this;
    }


    public function isDisplayed()
    {
        if (!$this->isPresent()) {
            return false;
        }
        try {
            // Presence is already checked, so we need just minimal timeout to check visibility.
            $this->webDriver->timeout()->implicitWait(1);
            $result = $this->sendCommand('element/:id/displayed', WebDriver_Command::METHOD_GET);
        } finally {
            $this->restoreImplicitWait();
        }
        return (bool) $result['value'];
    }


    public function waitPresent($timeout = null, $message = null)
    {
        $exception = null;
        $timeout = $timeout ? $timeout : $this->waitTimeout;
        try {
            $this->webDriver->timeout()->implicitWait(1000 * $timeout);
            $this->getElementId();
        } catch (WebDriver_Exception $exception) {
            if ($message !== null) {
                $exception->setMessage(
                    "{$exception->getMessage()}\nElement not found: {$this->getLocator()} with error: {$message}"
                );
            }
        } finally {
            $this->restoreImplicitWait();
        }
        if (null !== $exception) {
            throw $exception;
        }
        return $this;
    }


    public function timeout($timeout = 30)
    {
        $this->waitTimeout = $timeout;
        return $this;
    }


    public function setPresentTimeout($timeout = 1)
    {
        $this->presentTimeout = $timeout;
        return $this;
    }


    /**
     * @return $this
     * @throws WebDriver_Exception
     */
    public function waitDisplayed()
    {
        return $this->wait(
            function (WebDriver_Element $element) {
                return $element->isDisplayed();
            },
            "Element '{$this->locator}' is not displayed after timeout"
        );
    }


    /**
     * @return $this
     * @throws WebDriver_Exception
     */
    public function waitHidden()
    {
        $this->wait(
            function (WebDriver_Element $element) {
                return !$element->isDisplayed();
            },
            "Element '{$this->locator}' is not hidden after timeout"
        );
        return $this;
    }


    /**
     * Ждёт, пока коллбэк не вернёт TRUE или не кончится таймаут.
     *
     * @param callable $callback Принимает текущий элемент в виде единственного парамтера.
     * @param string|null $message
     * @return WebDriver_Wait Специально не упоминаем $this, чтобы переделывали везде на правильный wait.
     * @throws WebDriver_Exception
     */
    public function wait($callback = null, $message = null)
    {
        if ($callback === null) {
            return new WebDriver_Wait($this->webDriver, $this);
        }
        for ($i = 0; $i < $this->waitTimeout; $i++) {
            if (call_user_func($callback, $this)) {
                return $this;
            }
            sleep(1);
        }

        throw new WebDriver_Exception(
            null === $message
            ? "Provided state of element '{$this->locator}' is not reached after timeout"
            : $message
        );
    }


    public function touch()
    {
        return new WebDriver_Element_Touch($this->webDriver, $this);
    }


    public function getImage()
    {
        if (null !== $this->image) {
            if (is_resource($this->image)) {
                imagedestroy($this->image);
            }
            $this->image = null;
        }
        $image = imagecrop($this->getCachedScreenshot(), $this->location() + $this->size());
        if (false === $image) {
            throw new WebDriver_Exception("Failed to crop screenshot image");
        }
        $this->image = $image;
        return $this->image;
    }


    public function getImageColorAt($x, $y)
    {
        $image = $this->getCachedScreenshot();
        $size = $this->size();
        if ($x < 0 || $x >= $size['width'] || $y < 0 || $y >= $size['height']) {
            return 0; // outside of picture
        }
        $location = $this->location();
        return imagecolorat($image, $x + $location['x'], $y + $location['y']);
    }


    /**
     * @return resource
     * @throws WebDriver_Exception
     */
    protected function getCachedScreenshot()
    {
        $cache = $this->webDriver->cache();
        $image = $cache->get($cache::RESOURCE_SCREENSHOT);
        if (!is_resource($image) || 'gd' != get_resource_type($image)) {
            throw new WebDriver_Exception("Invalid screenshot image resource in WebDriver cache");
        }
        return $image;
    }
    /*
    /session/:sessionId/element/:id/attribute/:name
    equals
    location_in_view
    css
    */
}
