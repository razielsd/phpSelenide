<?php

class WebDriver_Touch
{

    /**
     * @var WebDriver
     */
    protected $webDriver = null;


    public function __construct(WebDriver $webDriver)
    {
        $this->webDriver = $webDriver;
    }


    /**
     * Performs swipe on screen.
     *
     * @param int $xSpeed
     * @param int $ySpeed
     * @return $this
     */
    public function swipe($xSpeed, $ySpeed)
    {
        $this->webDriver->getDriver()->curl(
            $this->webDriver->getDriver()->factoryCommand(
                'touch/flick',
                WebDriver_Command::METHOD_POST,
                [
                    'xspeed' => (int) $xSpeed,
                    'yspeed' => (int) $ySpeed,
                ]
            )
        );
        return $this;
    }


    /**
     * Performs touch on screen.
     *
     * @param int $x
     * @param int $y
     * @return $this
     */
    public function down($x, $y)
    {
        $this->webDriver->getDriver()->curl(
            $this->webDriver->getDriver()->factoryCommand(
                'touch/down',
                WebDriver_Command::METHOD_POST,
                [
                    'x' => (int) $x,
                    'y' => (int) $y,
                ]
            )
        );
        return $this;
    }


    /**
     * Untouches the screen.
     *
     * @param $x
     * @param $y
     * @return $this
     */
    public function up($x, $y)
    {
        $this->webDriver->getDriver()->curl(
            $this->webDriver->getDriver()->factoryCommand(
                'touch/up',
                WebDriver_Command::METHOD_POST,
                [
                    'x' => (int) $x,
                    'y' => (int) $y,
                ]
            )
        );
        return $this;
    }


    /**
     * Moves point of touch.
     *
     * @param int $x
     * @param int $y
     * @return $this
     * @throws WebDriver_Exception
     * @throws WebDriver_NoSeleniumException
     */
    public function move($x, $y)
    {
        $this->webDriver->getDriver()->curl(
            $this->webDriver->getDriver()->factoryCommand(
                'touch/move',
                WebDriver_Command::METHOD_POST,
                [
                    'x' => (int) $x,
                    'y' => (int) $y,
                ]
            )
        );
        return $this;
    }
}
