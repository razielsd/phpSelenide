<?php

class WebDriver_Command
{
    const METHOD_POST   = 'POST';
    const METHOD_GET    = 'GET';
    const METHOD_DELETE = 'DELETE';

    protected $command   = null;
    protected $method    = null;
    protected $params    = null;
    protected $sessionId = null;
    protected $serverUrl = null;


    public function __construct($command, $method, $params = array())
    {
        $this->command = $command;
        $this->method = $method;
        $this->params = $params;
    }


    public function addSession($serverUrl, $sessionId)
    {
        $this->sessionId = $sessionId;
        $this->serverUrl = $serverUrl;
    }


    public function param($params)
    {
        foreach ($params as $search => $replace) {
            $this->command = str_replace(':' . $search, $replace, $this->command);
        }
        return $this;
    }


    public function withoutSession()
    {
        $this->sessionId = null;
        return $this;
    }


    public function getUrl()
    {
        $url = $this->serverUrl;
        if ($this->sessionId) {
            $url .= 'session/' . $this->sessionId . '/';
        }
        $url .= $this->command;
        return $url;
    }


    public function getCommandName()
    {
        return $this->command;
    }


    public function getMethod()
    {
        return $this->method;
    }


    public function getParameters()
    {
        return $this->params;
    }


    public function __toString()
    {
        $str = '[' . $this->getMethod() . ']' . $this->getCommandName() . '(' .
            json_encode($this->getParameters()) . ')';
        return $str;
    }
}
