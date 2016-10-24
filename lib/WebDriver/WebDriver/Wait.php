<?php

class WebDriver_Wait
{

    /**
     * @var WebDriver
     */
    protected $webDriver = null;

    /**
     * @var WebDriver_Element
     */
    protected $element = null;

    protected $errorMessage = null;

    protected $timeout = 30;

    protected $defaultTimeout = 30;

    /**
     * @var callable|null
     */
    protected $callback = null;


    public function __construct(WebDriver $webDriver, WebDriver_Element $element)
    {
        $this->webDriver = $webDriver;
        $this->element = $element;
    }


    /**
     * @return WebDriver_Element
     */
    public function element()
    {
        return $this->element;
    }


    /**
     * Sets error message.
     *
     * @param string $message
     * @return $this
     */
    public function message($message)
    {
        $this->errorMessage = $message;
        return $this;
    }


    /**
     * Appends error message.
     *
     * @param $message
     * @return $this
     */
    public function appendMessage($message)
    {
        $this->errorMessage = null === $this->errorMessage ? $message : "{$this->errorMessage} {$message}";
        return $this;
    }


    /**
     * Sets timeout.
     *
     * @param null $timeout
     * @return $this
     */
    public function timeout($timeout = null)
    {
        $this->timeout = (null === $timeout) ? $this->defaultTimeout : $timeout;
        return $this;
    }


    /**
     * @return $this
     * @throws Exception
     * @throws WebDriver_Exception
     */
    public function isPresent()
    {
        $exception = null;
        try {
            $this->webDriver->timeout()->implicitWait(1000 * $this->timeout);
            $this->element()->getElementId();
        } catch (WebDriver_Exception $e) {
            $exception = null === $this->errorMessage
                ? $e
                : new WebDriver_Exception(
                    "Element not found: {$this->element->getLocator()} with error: {$this->errorMessage}"
                );
        } finally {
            $this->restoreImplicitWait();
        }
        if (null !== $exception) {
            throw $exception;
        }
        return $this;
    }


    /**
     * @return $this
     * @throws WebDriver_Exception
     */
    public function isDisplayed()
    {
        return $this->callback(function (WebDriver_Element $element) {
            return $element->isDisplayed();
        });
    }


    /**
     * @return $this
     * @throws WebDriver_Exception
     */
    public function isHidden()
    {
        return $this->callback(function (WebDriver_Element $element) {
            return !$element->isDisplayed();
        });
    }


    /**
     * Sets callback and performs waiting.
     *
     * @param callable $callback
     * @return $this
     * @throws WebDriver_Exception
     */
    public function callback(callable $callback)
    {
        return $this
            ->addCallback($callback)
            ->execute();
    }


    /**
     * Sets waiting callback.
     *
     * @param callable $callback
     * @return $this
     */
    public function addCallback(callable $callback)
    {
        $this->callback = $callback;
        return $this;
    }


    /**
     * Performs waiting using current callback.
     *
     * @return $this
     * @throws WebDriver_Exception
     */
    public function execute()
    {
        $locator = $this->element->getLocator();
        if (null === $this->callback) {
            throw new WebDriver_Exception("Wait callback is undefined for element '{$locator}''");
        }
        for ($i = 0; $i < $this->timeout; $i++) {
            if (call_user_func($this->callback, $this->element())) {
                return $this;
            }
            sleep(1);
        }
        $message = "Provided state of element '{$locator}' is not reached after timeout";
        $message = ($this->errorMessage == null) ? $message : "{$message} with error: {$this->errorMessage}";
        throw new WebDriver_Exception($message);
    }


    protected function restoreImplicitWait()
    {
        $this->webDriver->timeout()->implicitWait($this->defaultTimeout * 1000);
        return $this;
    }

}