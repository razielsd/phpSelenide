<?php

/**
 * Invalid Request Errors.
 *
 * All invalid requests should result in the server returning a 4xx HTTP response.
 * The response Content-Type should be set to text/plain and the message body should be a descriptive error message.
 *
 * If an individual command has not been implemented on the server, the server should respond with a
 * 501 Not Implemented error message. Note this is the only error in the Invalid Request category that does not
 * return a 4xx status code.
 *
 * @see https://code.google.com/p/selenium/wiki/JsonWireProtocol#Error_Handling
 */
class WebDriver_Exception_InvalidRequest extends WebDriver_Exception_Protocol
{


    /**
     * Factory for Invalid Request errors.
     *
     * @param WebDriver_Command $command
     * @param array $infoList
     * @param null|string $rawResponse
     * @return static
     */
    final public static function factory(WebDriver_Command $command, array $infoList = [], $rawResponse = null)
    {
        $httpStatusCode = isset($infoList['http_code']) ? $infoList['http_code'] : 0;
        $messageList = [
            $rawResponse,
            static::getCommandDescription($command)
        ];
        $message = implode("\n", array_merge(["WebDriver Invalid Request Error: {$httpStatusCode}"], $messageList));
        $exception = new WebDriver_Exception_InvalidRequest($message);
        $exception->httpStatusCode = $httpStatusCode;
        return $exception;
    }
}
