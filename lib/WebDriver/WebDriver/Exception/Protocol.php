<?php

/**
 * Base exception for all jsonWireProtocol Errors
 *
 */
class WebDriver_Exception_Protocol extends WebDriver_Exception
{

    /**
     * HTTP status code of selenium server response when this exception had been thrown.
     *
     * @var int
     */
    protected $httpStatusCode = 0;


    /**
     * Returns HTTP status code.
     *
     * @return int
     */
    public function getHttpStatusCode()
    {
        return $this->httpStatusCode;
    }


    /**
     * Returns webdriver command description.
     *
     * @param WebDriver_Command $command
     * @return string
     */
    public static function getCommandDescription(WebDriver_Command $command)
    {
        $commandDescriptionList = [
            "Command: {$command->getCommandName()}",
            "Method: {$command->getMethod()}",
            "URL: {$command->getUrl()}"
        ];
        $paramList = $command->getParameters();
        if (!empty($paramList)) {
            $commandDescriptionList = array_merge(
                $commandDescriptionList,
                ["Parameters:", var_export($paramList, true)]
            );
        }
        return implode("\n", $commandDescriptionList);
    }


    /**
     * Factory for protocol errors.
     *
     * @param WebDriver_Command $command
     * @param array $infoList
     * @param null|string $rawResponse
     * @return static
     */
    public static function factory(WebDriver_Command $command, array $infoList = [], $rawResponse = null)
    {
        $httpStatusCode = isset($infoList['http_code']) ? $infoList['http_code'] : 0;
        switch ($httpStatusCode) {
            case WebDriver_Http::CLIENT_ERROR_NOT_FOUND:
            case WebDriver_Http::SERVER_ERROR_NOT_IMPLEMENTED:
            case WebDriver_Http::CLIENT_ERROR_METHOD_NOT_ALLOWED:
            case WebDriver_Http::CLIENT_ERROR_BAD_REQUEST:
                return WebDriver_Exception_InvalidRequest::factory($command, $infoList, $rawResponse);
            case WebDriver_Http::SERVER_ERROR_INTERNAL:
                $decodedJsonList = json_decode($rawResponse, true);
                $messageList = ["Internal Server Error"];
                if ($decodedJsonList === null) {
                    $messageList[] = "Cant decode response JSON:";
                    $messageList[] = json_last_error_msg();
                    $messageList[] = $rawResponse;
                    $exception = new WebDriver_Exception_Protocol(implode("\n", $messageList));
                    $exception->httpStatusCode = $httpStatusCode;
                    return $exception;
                }
                $exception = WebDriver_Exception_FailedCommand::factory(
                    $command,
                    $infoList,
                    $rawResponse,
                    $decodedJsonList
                );
                $exception->httpStatusCode = $httpStatusCode;
                return $exception;
            default:
                $messageList = [
                    "Unknown error: {$httpStatusCode}",
                    $rawResponse,
                    static::getCommandDescription($command)
                ];
                $exception = new WebDriver_Exception_Protocol(implode("\n", $messageList));
                $exception->httpStatusCode = $httpStatusCode;
                return $exception;
        }
    }
}
