<?php
class WebDriver_Object_Alert extends WebDriver_Object
{

    /**
     * Gets the text of the currently displayed JavaScript alert(), confirm(), or prompt() dialog.
     */
    public function text()
    {
        $command = $this->driver->factoryCommand('alert_text', WebDriver_Command::METHOD_GET);
        return $this->driver->curl($command)['value'];
    }


    /**
     * Sends keystrokes to a JavaScript prompt() dialog.
     *
     * @param string $str
     */
    public function write($str, $isAccept=true)
    {
        $params = ['text' => "{$str}"];
        $command = $this->driver->factoryCommand('alert_text', WebDriver_Command::METHOD_POST, $params);
        $result =  $this->driver->curl($command)['value'];
        if ($isAccept) {
            $this->accept();
        }
        return $result;
    }


    /**
     * Accepts the currently displayed alert dialog.
     */
    public function accept()
    {
        $command = $this->driver->factoryCommand('accept_alert', WebDriver_Command::METHOD_POST);
        return $this->driver->curl($command)['value'];
    }


    /**
     * Dismisses the currently displayed alert dialog.
     */
    public function dismiss()
    {
        $command = $this->driver->factoryCommand('dismiss_alert', WebDriver_Command::METHOD_POST);
        return $this->driver->curl($command)['value'];
    }


    /**
     * Waits for an alert to appear.
     *
     * @param int $timeout
     * @return $this
     * @throws WebDriver_Exception
     */
    public function wait($timeout = 30)
    {
        $lastException = null;
        for ($i = 0; $i < $timeout; $i++) {
            try {
                $this->text();
                return $this;
            } catch (WebDriver_Exception $e) {
                $lastException = $e;
                sleep(1);
            }
        }

        if (null !== $lastException) {
            throw $lastException;
        }

        return $this;
    }
}
