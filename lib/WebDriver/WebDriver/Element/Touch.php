<?php

class WebDriver_Element_Touch
{

    /**
     * @var WebDriver
     */
    protected $webDriver = null;

    /**
     * @var WebDriver_Element
     */
    protected $element = null;


    public function __construct(WebDriver $webDriver, WebDriver_Element $element)
    {
        $this->webDriver = $webDriver;
        $this->element = $element;
    }


    /**
     * Single tap on the touch enabled device.
     *
     * @return $this
     * @throws WebDriver_Exception
     * @throws WebDriver_NoSeleniumException
     */
    public function click()
    {
        $command = $this->webDriver->getDriver()->factoryCommand(
            'touch/click',
            WebDriver_Command::METHOD_POST,
            ['element' => $this->element->getElementId()]
        );
        $this->webDriver->getDriver()->curl($command);
        return $this;
    }


    /**
     * Double tap on the touch screen using finger motion events.
     *
     * @return $this
     * @throws WebDriver_Exception
     * @throws WebDriver_NoSeleniumException
     */
    public function doubleClick()
    {
        $command = $this->webDriver->getDriver()->factoryCommand(
            'touch/doubleclick',
            WebDriver_Command::METHOD_POST,
            ['element' => $this->element->getElementId()]
        );
        $this->webDriver->getDriver()->curl($command);
        return $this;
    }


    /**
     * Long press on the touch screen using finger motion events.
     *
     * @return $this
     * @throws WebDriver_Exception
     * @throws WebDriver_NoSeleniumException
     */
    public function longClick()
    {
        $command = $this->webDriver->getDriver()->factoryCommand(
            'touch/longclick',
            WebDriver_Command::METHOD_POST,
            ['element' => $this->element->getElementId()]
        );
        $result = $this->webDriver->getDriver()->curl($command);
        return $this;
    }


    /**
     * Scroll on the touch screen using finger based motion events. Use this command to start scrolling at a particular
     * screen location.
     *
     * @param int $xOffset
     * @param int $yOffset
     * @return $this
     * @throws WebDriver_Exception
     * @throws WebDriver_NoSeleniumException
     */
    public function scroll($xOffset, $yOffset)
    {
        $command = $this->webDriver->getDriver()->factoryCommand(
            'touch/scroll',
            WebDriver_Command::METHOD_POST,
            [
                'element' => $this->element->getElementId(),
                'xoffset' => (int) $xOffset,
                'yoffset' => (int) $yOffset,
            ]
        );
        $this->webDriver->getDriver()->curl($command);
        return $this;
    }


    /**
     * Flick on the touch screen using finger motion events. This flickcommand starts at a particulat screen location.
     *
     * @param int $xOffset
     * @param int $yOffset
     * @param int $speed
     * @return $this
     * @throws WebDriver_Exception
     * @throws WebDriver_NoSeleniumException
     */
    public function flick($xOffset, $yOffset, $speed = WebDriver::SPEED_NORMAL)
    {
        $command = $this->webDriver->getDriver()->factoryCommand(
            'touch/flick',
            WebDriver_Command::METHOD_POST,
            [
                'element' => $this->element->getElementId(),
                'xoffset' => (int) $xOffset,
                'yoffset' => (int) $yOffset,
                'speed' => (int) $speed,
            ]
        );
        $this->webDriver->getDriver()->curl($command);
        return $this;
    }
}
