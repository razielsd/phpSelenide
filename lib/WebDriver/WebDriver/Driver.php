<?php

class WebDriver_Driver
{

    protected $host = null;

    protected $port = null;

    protected $requestTimeout = 60;

    protected $sessionRequestTimeout = 60;

    protected $desiredCapabilities = [
        'browserName' => 'firefox',
    ];

    protected $sessionId = null;

    protected $serverUrl = null;

    protected $isCloseSession = false;

    protected $isDebug = false;


    protected function __construct($host, $port = 4444, $desiredCapabilities = [])
    {
        $this->desiredCapabilities = empty($desiredCapabilities) ? $this->desiredCapabilities : $desiredCapabilities;
        $this->host = $host;
        $this->port = $port;
        $this->serverUrl = "http://{$this->host}:{$this->port}/wd/hub/";
    }


    public static function factory($host, $port = 4444, $desiredCapabilities = null)
    {
        return new static($host, $port, $desiredCapabilities);
    }


    public function clearDesiredCapabilities()
    {
        $this->desiredCapabilities = [];
        return $this;
    }


    public function setDesiredCapability($key, $value = null)
    {
        /** @var WebDriver_Capabilities $capabilities */
        $capabilities = $key;
        if ($capabilities instanceof WebDriver_Capabilities) {
            $this->desiredCapabilities = $capabilities->asArray();
            return $this;
        }
        if (null === $value) {
            unset($this->desiredCapabilities[$key]);
        } else {
            $this->desiredCapabilities[$key] = $value;
        }
        return $this;
    }


    public function connect($sessionId = null)
    {
        if (null === $sessionId) {
            $defaultTimeout = $this->requestTimeout;
            $this->requestTimeout = $this->sessionRequestTimeout;
            $result = $this->curl(
                $this->factoryCommand(
                    'session',
                    WebDriver_Command::METHOD_POST,
                    ['desiredCapabilities' => $this->desiredCapabilities]
                )
            );
            $sessionId = $result['sessionId'];
            $this->requestTimeout = $defaultTimeout;
            $this->isCloseSession = true;
        }
        $this->sessionId = $sessionId;
        return $this;
    }


    /**
     * Возвращает список сессий.
     * @return array
     */
    public function getSessionList()
    {
        $result = $this->curl(
            $this->factoryCommand(
                "sessions",
                \WebDriver_Command::METHOD_GET
            )
        );
        return (array) $result['value'];
    }


    /**
     * Удаляет сессию по идентификатору.
     * @param string $sessionId
     * @return $this
     */
    public function deleteSession(string $sessionId)
    {
        $this->curl(
            $this->factoryCommand(
                "session/{$sessionId}",
                \WebDriver_Command::METHOD_DELETE
            )
        );
        return $this;
    }


    public function setRequestTimeout($timeout)
    {
        $this->requestTimeout = $timeout;
        return $this;
    }


    public function setSessionRequestTimeout($timeout)
    {
        $this->sessionRequestTimeout = $timeout;
        return $this;
    }


    public function __destruct()
    {
        $this->close();
    }


    public function close()
    {
        if (!empty($this->sessionId) && $this->isCloseSession) {
            $command = $this->factoryCommand(
                'session/' . $this->sessionId,
                WebDriver_Command::METHOD_DELETE
            )->withoutSession();
            try {
                $this->curl($command);
            } finally {
                $this->sessionId = null;
            }
        }
        return $this;
    }


    public function factoryCommand($command, $method, $params = array())
    {
        $command = new WebDriver_Command($command, $method, $params);
        $command->addSession($this->serverUrl, $this->sessionId);
        return $command;
    }


    public function curl(WebDriver_Command $command)
    {
        $url = $command->getUrl();
        $this->writeLog('URL: ' . $url);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->requestTimeout);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-type: application/json;charset=UTF-8',
                'Accept: application/json;charset=UTF-8'
            )
        );

        $method = $command->getMethod();
        $this->writeLog('Request method: ' . $method);
        $params = $command->getParameters();
        $this->writeLog('Params: ' . var_export($params, true));
        if ($method === WebDriver_Command::METHOD_POST) {
            curl_setopt($ch, CURLOPT_POST, true);
            if ($params && is_array($params)) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
            }
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        } elseif ($method === WebDriver_Command::METHOD_DELETE) {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        }
        $rawResponse = trim(curl_exec($ch));
        if (curl_errno($ch)) {
            throw new WebDriver_NoSeleniumException(
                'Error connection[' . curl_errno($ch) . '] to ' .
                $url  . ': ' . curl_error($ch)
            );
        }
        $info = curl_getinfo($ch);
        curl_close($ch);
        return $this->parseResponse($rawResponse, $info, $command);
    }


    /**
     * Returns parsed response.
     *
     * @param string $rawResponse
     * @param array $infoList
     * @param WebDriver_Command $command
     * @throws WebDriver_Exception
     * @throws WebDriver_NoSeleniumException
     * @return array
     */
    protected function parseResponse($rawResponse, $infoList, $command)
    {
        $httpStatusCode = isset($infoList['http_code']) ? $infoList['http_code'] : 0;
        $messageList = [
            "HTTP Response Status Code: {$httpStatusCode}",
            WebDriver_Exception_Protocol::getCommandDescription($command)
        ];
        switch ($httpStatusCode) {
            case 0:
                $message = $this->buildResponseErrorMessage("No response or broken.", $messageList);
                throw new WebDriver_NoSeleniumException($message);
            case WebDriver_Http::SUCCESS_OK:
                $decodedJsonList = json_decode($rawResponse, true);
                if ($decodedJsonList === null) {
                    $errorList = ["Can't decode response JSON:", json_last_error_msg()];
                    $message = $this->buildResponseErrorMessage($errorList, $messageList);
                    throw new WebDriver_Exception_Protocol($message);
                }
                if (isset($decodedJsonList['status']) &&
                    !WebDriver_Exception_FailedCommand::isOk($decodedJsonList['status'])) {
                    throw WebDriver_Exception_FailedCommand::factory(
                        $command,
                        $infoList,
                        $rawResponse,
                        $decodedJsonList
                    );
                }
                return $decodedJsonList;
            default:
                if (WebDriver_Http::isError($httpStatusCode)) {
                    throw WebDriver_Exception_Protocol::factory($command, $infoList, $rawResponse);
                }
                $errorMessage = "Unexpected HTTP status code: {$httpStatusCode}";
                $message = $this->buildResponseErrorMessage($errorMessage, $messageList);
                throw new WebDriver_Exception($message);
        }
    }


    /**
     * Returns error message.
     *
     * @param string|array $errorMessageList
     * @param string|array $dataList
     * @return string
     */
    protected function buildResponseErrorMessage($errorMessageList, array $dataList)
    {
        return implode("\n", array_merge((array) $errorMessageList, $dataList));
    }


    protected function writeLog($logTxt)
    {
        if ($this->isDebug) {
            //echo "{$logTxt}\n";
            error_log($logTxt);
        }

    }


    public function status()
    {
        return $this->curl($this->factoryCommand('status', WebDriver_Command::METHOD_GET));
    }
}
