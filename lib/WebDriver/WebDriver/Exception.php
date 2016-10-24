<?php
class WebDriver_Exception extends Exception
{

    /**
     * Set custom error message, you can use for adding additional info(current url, screenshot path and etc.)
     *
     * @param $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }
}
