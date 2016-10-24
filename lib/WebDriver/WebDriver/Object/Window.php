<?php
class WebDriver_Object_Window extends WebDriver_Object
{

    /**
     * This object window handle.
     *
     * @var string
     */
    protected $windowHandle = 'current';


    /**
     * Waiting for new window after callback executes.
     *
     * @param callable $callback
     * @return $this
     * @throws WebDriver_Exception
     */
    public function open(callable $callback)
    {
        $sessionHandleList = $this->getSessionWindowHandles();
        $callback($this->driver);
        for ($i = 0; $i < 10; $i++) {
            $handle = $this->getNewWindow($sessionHandleList);
            if ($handle === null) {
                sleep(1);
            } else {
                $this->setWindowHandle($handle);
                return $this;
            }
        }
        throw new WebDriver_Exception("The expected window didn't open.");
    }


    /**
     * Get the current active window handle.
     * It may differ from this object window handle.
     *
     * @return string
     * @throws WebDriver_Exception
     * @throws WebDriver_NoSeleniumException
     */
    public function getCurrentWindowHandle()
    {
        $command = $this->driver->factoryCommand('window_handle', WebDriver_Command::METHOD_GET);
        $result = $this->driver->curl($command);
        return $result['value'];
    }


    /**
     * Get all window handles of this webdriver session.
     *
     * @return array
     * @throws WebDriver_Exception
     * @throws WebDriver_NoSeleniumException
     */
    public function getSessionWindowHandles()
    {
        $command = $this->driver->factoryCommand('window_handles', WebDriver_Command::METHOD_GET);
        $result = $this->driver->curl($command);
        return $result['value'];
    }


    /**
     * Get new window handle.
     *
     * @throws WebDriver_Exception
     * @param array $sessionHandleList
     * @return string|null
     */
    protected function getNewWindow($sessionHandleList)
    {
        $newSessionHandleList = $this->getSessionWindowHandles();
        $sessionHandleCount = count($sessionHandleList);
        $newSessionHandleCount = count($newSessionHandleList);
        $newWindowHandleList = array_diff($newSessionHandleList, $sessionHandleList);
        if ($newSessionHandleCount - $sessionHandleCount == 1 && count($newWindowHandleList) == 1) {
            /**
             * If amount of current session handles increments exactly by 1 and new handle only 1 -
             * this is new window handle.
             */
            return array_shift($newWindowHandleList);
        } elseif ($newSessionHandleCount == $sessionHandleCount && count($newWindowHandleList) == 0) {
            return;
        } else {
            throw new WebDriver_Exception(
                "Unexpected behaviour while waiting for new window: \n" .
                "Handles before wait started: " . var_export($sessionHandleList, true) . "\n" .
                "Handles current: " . var_export($newSessionHandleList, true)
            );
        }
    }


    /**
     * Set this window active
     *
     * @return $this
     */
    public function focus()
    {
        $params = ['name' => $this->windowHandle];
        $command = $this->driver->factoryCommand('window', WebDriver_Command::METHOD_POST, $params);
        $this->driver->curl($command);
        return $this;
    }


    /**
     * Close this window
     *
     * @return $this
     */
    public function close()
    {
        $params = ['name' => $this->windowHandle];
        $command = $this->driver->factoryCommand('window', WebDriver_Command::METHOD_DELETE, $params);
        $this->driver->curl($command);
        return $this;
    }


    /**
     * Set window handle for window
     *
     * @param $windowHandle
     * @return $this
     */
    public function setWindowHandle($windowHandle)
    {
        $this->windowHandle = $windowHandle;
        return $this;
    }


    /**
     * Get the size of the specified window.
     *
     * @return array
     */
    public function getSize()
    {
        $command = $this->driver->factoryCommand(
            'window/' . $this->windowHandle . '/size',
            WebDriver_Command::METHOD_GET
        );
        $value = $this->driver->curl($command)['value'];
        return ['height' => $value['height'], 'width' => $value['width']];
    }


    /**
     * Change the size of the specified window.
     *
     * @param $width
     * @param $height
     * @return mixed
     */
    public function setSize($width, $height)
    {
        $params = [
            'width' => intval($width),
            'height' => intval($height)
        ];
        $command = $this->driver->factoryCommand(
            'window/' . $this->windowHandle . '/size',
            WebDriver_Command::METHOD_POST,
            $params
        );
        return $this->driver->curl($command)['value'];
    }


    /**
     * Maximize the specified window if not already maximized.
     *
     * @return mixed
     */
    public function maximize()
    {
        $command = $this->driver->factoryCommand(
            'window/' . $this->windowHandle . '/maximize',
            WebDriver_Command::METHOD_POST
        );
        return $this->driver->curl($command)['value'];
    }


    /**
     * Get the position of the specified window.
     *
     * @return array
     */
    public function getPosition()
    {
        $command = $this->driver->factoryCommand(
            'window/' . $this->windowHandle . '/position',
            WebDriver_Command::METHOD_GET
        );
        $value = $this->driver->curl($command)['value'];
        return ['left' => $value['x'], 'top' => $value['y']];

    }


    /**
     * Change the position of the specified window.
     *
     * @param $left
     * @param $top
     * @return mixed
     */
    public function setPosition($left, $top)
    {
        $params = [
            'x' => intval($left),
            'y' => intval($top)
        ];
        $command = $this->driver->factoryCommand(
            'window/' . $this->windowHandle . '/position',
            WebDriver_Command::METHOD_POST,
            $params
        );
        return $this->driver->curl($command)['value'];
    }
}
