<?php
namespace Selenide;


class Configuration
{
    public $host = '127.0.0.1';
    public $port = 4444;
    public $timeout = 5;
    /**
     * Selenium session id for reuse active session
     * @var string
     */
    public $sessionId = null;
}
