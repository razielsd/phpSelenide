<?php
namespace Selenide;


class Configuration
{
    public $host = '127.0.0.1';
    public $port = 4444;
    public $timeout = 5;
    public $waitTimeout = 30;

    /**
     * Selenium session id for reuse active session
     * @var string
     */
    public $sessionId = null;
    /**
     * Base url for open() function calls Default value: http://localhost
     * @var string
     */
    public $baseUrl = 'http://localhost';
}
