<?php

class WebDriver_Server_Selendroid extends WebDriver
{

    // special android keys
    const KEY_DPAD_LEFT    = 0xE012;
    const KEY_DPAD_UP      = 0xE013;
    const KEY_DPAD_RIGHT   = 0xE014;
    const KEY_DPAD_DOWN    = 0xE017;
    const KEY_BACK         = 0xE100;
    const KEY_ANDROID_HOME = 0xE101;
    const KEY_MENU         = 0xE102;
    const KEY_SEARCH       = 0xE103;
    const KEY_SYM          = 0xE104;
    const KEY_ALT_RIGHT    = 0xE105;
    const KEY_SHIFT_RIGHT  = 0xE106;

    // commands
    const COMMAND_SEND_KEYS_TO_ELEMENT = 'sendKeysToElement';

    // properties
    const PROPERTY_NATIVE_EVENTS = 'nativeEvents';

    // touch actions
    const ACTION_POINTER_DOWN = 'pointerDown';
    const ACTION_POINTER_UP = 'pointerUp';
    const ACTION_POINTER_MOVE = 'pointerMove';
    const ACTION_POINTER_CANCEL = 'pointerCancel';
    const ACTION_PAUSE = 'pause';
    const ACTION_FLICK = 'flick';

    // flick directions
    const DIRECTION_UP = 'up';
    const DIRECTION_DOWN = 'down';
    const DIRECTION_LEFT = 'left';
    const DIRECTION_RIGHT = 'right';

    protected $sessionStartAttempts = 30;
    protected $sessionStartAttemptTimeout = 1;


    /**
     * Gets command configuration.
     *
     * @param string $command
     * @return array
     */
    public function getCommandConfiguration($command)
    {
        $url = "selendroid/configure/command/{$command}";
        $result = $this->driver->curl($this->driver->factoryCommand($url, WebDriver_Command::METHOD_GET));
        return isset($result['value']) ? $result['value'] : [];
    }


    /**
     * Sets command configuration.
     *
     * @param string $command
     * @param array $properties
     * @return $this
     */
    public function setCommandConfiguration($command, array $properties)
    {
        $url = "selendroid/configure/command/{$command}";
        $properties['command'] = $command;
        $this->driver->curl($this->driver->factoryCommand($url, WebDriver_Command::METHOD_POST, $properties));
        return $this;
    }


    /**
     * @return WebDriver_Server_Selendroid_Touch
     */
    public function touch()
    {
        return new WebDriver_Server_Selendroid_Touch($this);
    }


    public function connect($sessionId = null)
    {
        for ($i = 0; $i < $this->sessionStartAttempts; $i++ ) {
            try {
                $this->getDriver()->connect($sessionId);
                break;
            } catch (WebDriver_Exception_FailedCommand $e) {
                sleep($this->sessionStartAttemptTimeout);
            }
        }
        return $this;
    }
}