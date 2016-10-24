<?php

/**
 * Failed Command Errors.
 *
 * If a request maps to a valid command and contains all of the expected parameters in the request body, yet fails to
 * execute successfully, then the server should send a 500 Internal Server Error.
 * This response should have a Content-Type of application/json;charset=UTF-8 and the response body should be a well
 * formed JSON response object.
 *
 * @see https://code.google.com/p/selenium/wiki/JsonWireProtocol#Error_Handling
 */
class WebDriver_Exception_FailedCommand extends WebDriver_Exception_Protocol
{

    const OK = 0;

    const NO_SUCH_DRIVER = 6;
    const NO_SUCH_ELEMENT = 7;
    const NO_SUCH_FRAME = 8;
    const UNKNOWN_COMMAND = 9;
    const STALE_ELEMENT_REFERENCE = 10;
    const ELEMENT_NOT_VISIBLE = 11;
    const INVALID_ELEMENT_STATE = 12;
    const UNKNOWN_ERROR = 13;
    const ELEMENT_IS_NOT_SELECTABLE = 15;
    const JAVASCRIPT_ERROR = 17;
    const XPATH_LOOKUP_ERROR = 19;
    const TIMEOUT_ERROR = 21;
    const NO_SUCH_WINDOW = 23;
    const INVALID_COOKIE_DOMAIN = 24;
    const UNABLE_TO_SET_COOKIE = 25;
    const UNEXPECTED_ALERT_OPEN = 26;
    const NO_ALERT_OPEN = 27;
    const SCRIPT_TIMEOUT_ERROR = 28;
    const INVALID_ELEMENT_COORDINATES = 29;
    const IME_NOT_AVAILABLE = 30;
    const IME_ENGINE_ACTIVATION_FAILED = 31;
    const INVALID_SELECTOR = 32;
    const SESSION_NOT_CREATED_EXCEPTION = 33;
    const MOVE_TARGET_OUT_OF_BOUNDS = 34;


    protected $responseList;


    /**
     * List of error status messages.
     *
     * @var array
     */
    protected static $errorStatusList = [
        self::NO_SUCH_DRIVER =>
            'A session is either terminated or not started',
        self::NO_SUCH_ELEMENT =>
            'NoSuchElement - An element could not be located on the page using the given search parameters.',
        self::NO_SUCH_FRAME =>
            'NoSuchFrame - A request to switch to a frame could not be satisfied because the frame could not be found.',
        self::UNKNOWN_COMMAND =>
            'UnknownCommand - The requested resource could not be found, or a request was received using an HTTP ' .
            'method that is not supported by the mapped resource.',
        self::STALE_ELEMENT_REFERENCE =>
            'StaleElementReference - An element command failed because the referenced element is no longer ' .
            'attached to the DOM.',
        self::ELEMENT_NOT_VISIBLE =>
            'ElementNotVisible - An element command could not be completed because the element is not visible ' .
            'on the page.',
        self::INVALID_ELEMENT_STATE =>
            'InvalidElementState - An element command could not be completed because the element is in an ' .
            'invalid state (e.g. attempting to click a disabled element).',
        self::UNKNOWN_ERROR =>
            'UnknownError - An unknown server-side error occurred while processing the command.',
        self::ELEMENT_IS_NOT_SELECTABLE =>
            'ElementIsNotSelectable - An attempt was made to select an element that cannot be selected.',
        self::JAVASCRIPT_ERROR =>
            'JavaScriptError - An error occurred while executing user supplied JavaScript.',
        self::XPATH_LOOKUP_ERROR =>
            'XPathLookupError - An error occurred while searching for an element by XPath.',
        self::TIMEOUT_ERROR =>
            'Timeout - An operation did not complete before its timeout expired.',
        self::NO_SUCH_WINDOW =>
            'NoSuchWindow - A request to switch to a different window could not be satisfied because the window ' .
            'could not be found.',
        self::INVALID_COOKIE_DOMAIN =>
            'InvalidCookieDomain - An illegal attempt was made to set a cookie under a different domain than the' .
            ' current page.',
        self::UNABLE_TO_SET_COOKIE =>
            'UnableToSetCookie - A request to set a cookie\'s value could not be satisfied.',
        self::UNEXPECTED_ALERT_OPEN =>
            'UnexpectedAlertOpen - A modal dialog was open, blocking this operation',
        self::NO_ALERT_OPEN =>
            'NoAlertOpenError - An attempt was made to operate on a modal dialog when one was not open.',
        self::SCRIPT_TIMEOUT_ERROR =>
            'ScriptTimeout - A script did not complete before its timeout expired.',
        self::INVALID_ELEMENT_COORDINATES =>
            'InvalidElementCoordinates - The coordinates provided to an interactions operation are invalid.',
        self::IME_NOT_AVAILABLE =>
            'IMENotAvailable - IME was not available.',
        self::IME_ENGINE_ACTIVATION_FAILED =>
            'IMEEngineActivationFailed - An IME engine could not be started.',
        self::INVALID_SELECTOR =>
            'InvalidSelector - Argument was an invalid selector (e.g. XPath/CSS).',
        self::SESSION_NOT_CREATED_EXCEPTION =>
            'SessionNotCreatedException - A new session could not be created.',
        self::MOVE_TARGET_OUT_OF_BOUNDS =>
            'MoveTargetOutOfBounds - Target provided for a move action is out of bounds. ',
    ];


    public static function isOk($status)
    {
        return $status == self::OK;
    }


    public static function isKnownError($status)
    {
        return array_key_exists($status, static::$errorStatusList);
    }


    public static function getErrorMessage($status)
    {
        if (static::isKnownError($status)) {
            return static::$errorStatusList[$status];
        }
        return null;
    }


    public function getResponseJsonAsArray()
    {
        return $this->responseList;
    }


    /**
     * Factory for all failed command exceptions.
     *
     * @param WebDriver_Command $command
     * @param array $infoList
     * @param null|string $rawResponse
     * @param null|array $responseJson
     * @return WebDriver_Exception_FailedCommand
     */
    final public static function factory(
        WebDriver_Command $command,
        array $infoList = [],
        $rawResponse = null,
        $responseJson = null
    ) {

        if (!isset($responseJson['status'])) {
            $errorMessage = "Unknown error";
        } elseif (static::isOk($responseJson['status'])) {
            $errorMessage = "Unexpected Ok status {$responseJson['status']}";
        } elseif (static::isKnownError($responseJson['status'])) {
            $errorMessage = static::getErrorMessage($responseJson['status']);
        } else {
            $errorMessage = "Unknown error status: {$responseJson['status']}";
        }
        $messageList = [$errorMessage];
        if (isset($responseJson['value']['message'])) {
            $messageList[] = $responseJson['value']['message'];
        }
        $messageList[] = static::getCommandDescription($command);
        $messageList[] = "HTTP Status Code: {$infoList['http_code']}";
        switch ($responseJson['status']) {
            case self::STALE_ELEMENT_REFERENCE:
                $e = new WebDriver_Exception_StaleElementReference(
                    implode("\n", $messageList),
                    $responseJson['status']
                );
                $e->responseList = $responseJson;
                return $e;
            default:
                $e = new WebDriver_Exception_FailedCommand(implode("\n", $messageList), $responseJson['status']);
                $e->responseList = $responseJson;
                return $e;
        }
    }
}
