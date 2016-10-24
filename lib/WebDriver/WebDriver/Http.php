<?php

/**
 * Class, represents HTTP status codes.
 */
class WebDriver_Http
{

    const INFO_CONTINUE = 100;
    const INFO_SWITCHING_PROTOCOLS = 101;

    const SUCCESS_OK = 200;
    const SUCCESS_CREATED = 201;
    const SUCCESS_ACCEPTED = 202;
    const SUCCESS_NON_AUTHORITATIVE_INFORMATION = 203;
    const SUCCESS_NO_CONTENT = 204;
    const SUCCESS_RESET_CONTENT = 205;
    const SUCCESS_PARTIAL_CONTENT = 206;

    const REDIRECT_MULTIPLE_CHOICES = 300;
    const REDIRECT_MOVED_PERMANENTLY = 301;
    const REDIRECT_FOUND = 302;
    const REDIRECT_SEE_OTHER = 303;
    const REDIRECT_NOT_MODIFIED = 304;
    const REDIRECT_USE_PROXY = 305;
    const REDIRECT_TEMPORARY = 307;

    const CLIENT_ERROR_BAD_REQUEST = 400;
    const CLIENT_ERROR_UNAUTHORIZED = 401;
    const CLIENT_ERROR_PAYMENT_REQUIRED = 402;
    const CLIENT_ERROR_FORBIDDEN = 403;
    const CLIENT_ERROR_NOT_FOUND = 404;
    const CLIENT_ERROR_METHOD_NOT_ALLOWED = 405;
    const CLIENT_ERROR_NOT_ACCEPTABLE = 406;
    const CLIENT_ERROR_PROXY_AUTHENTICATION_REQUIRED = 407;
    const CLIENT_ERROR_REQUEST_TIMEOUT = 408;
    const CLIENT_ERROR_CONFLICT = 409;
    const CLIENT_ERROR_GONE = 410;
    const CLIENT_ERROR_LENGTH_REQUIRED = 411;
    const CLIENT_ERROR_PRECONDITION_FAILED = 412;
    const CLIENT_ERROR_REQUEST_ENTITY_TOO_LARGE = 413;
    const CLIENT_ERROR_REQUEST_URI_TOO_LONG = 414;
    const CLIENT_ERROR_UNSUPPORTED_MEDIA_TYPE = 415;
    const CLIENT_ERROR_REQUESTED_RANGE_NOT_SATISFIABLE = 416;
    const CLIENT_ERROR_EXPECTATION_FAILED = 417;

    const SERVER_ERROR_INTERNAL = 500;
    const SERVER_ERROR_NOT_IMPLEMENTED = 501;
    const SERVER_ERROR_BAD_GATEWAY = 502;
    const SERVER_ERROR_SERVICE_UNAVAILABLE = 503;
    const SERVER_ERROR_GATEWAY_TIMEOUT = 504;
    const SERVER_ERROR_HTTP_VERSION_NOT_SUPPORTED = 505;


    public static function isOk($code)
    {
        return self::SUCCESS_OK === $code;
    }


    public static function isInformational($code)
    {
        return 1 == round($code / 100, 0);
    }


    public static function isSuccess($code)
    {
        return 2 == round($code / 100, 0);
    }


    public static function isRedirect($code)
    {
        return 3 == round($code / 100, 0);
    }


    public static function isClientError($code)
    {
        return 4 == round($code / 100, 0);
    }


    public static function isServerError($code)
    {
        return 5 == round($code / 100, 0);
    }


    public static function isError($code)
    {
        return static::isClientError($code) || static::isServerError($code);
    }
}
