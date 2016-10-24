<?php

class WebDriver_Server_Selendroid_Touch_ActionBuilder
{

    const MULTIPLIER_X = 'x';
    const MULTIPLIER_Y = 'y';

    protected $actionChain = [];

    protected $isDown = false;

    protected $directionList = [
        WebDriver_Server_Selendroid::DIRECTION_UP => [0, -1],
        WebDriver_Server_Selendroid::DIRECTION_DOWN => [0, 1],
        WebDriver_Server_Selendroid::DIRECTION_LEFT => [-1, 0],
        WebDriver_Server_Selendroid::DIRECTION_RIGHT => [1, 0],
    ];


    public function __construct()
    {
    }


    /**
     * Adds single action to current chain.
     *
     * @param string $name
     * @param array $params
     * @return $this
     */
    protected function addAction($name, array $params = [])
    {
        $this->actionChain[] = [
            'name' => $name,
        ] + $params;
        return $this;
    }


    /**
     * Build coordinate parameters for action.
     *
     * @param array|null $elementRef
     * @param int $x
     * @param int $y
     * @return array
     */
    protected function getCoordParams(array $elementRef = null, $x = 0, $y = 0)
    {
        return
            (array) $elementRef + [
                'x' => (int) $x,
                'y' => (int) $y,
            ];
    }


    /**
     * Throws exception if pointer down state is wrong.
     *
     * @param bool $isDown
     * @return $this
     * @throws WebDriver_Exception
     */
    protected function assertIsDown($isDown = true)
    {
        if ($this->isDown != $isDown) {
            $downText = $isDown ? "up" : "down";
            throw new WebDriver_Exception("Touch pointer should be {$downText} for this action");
        }
        return $this;
    }


    /**
     * Switches to given pointer down state, throwing exception if current state is wrong.
     *
     * @param bool $isDown
     * @return $this
     * @throws WebDriver_Exception
     */
    protected function switchIsDown($isDown = true)
    {
        $this->assertIsDown(!$isDown);
        $this->isDown = (bool) $isDown;
        return $this;
    }


    /**
     * Puts pointer down.
     *
     * @param array|null $elementRef
     * @param int $x
     * @param int $y
     * @return $this
     */
    public function down(array $elementRef = null, $x = 0, $y = 0)
    {
        return $this
            ->switchIsDown()
            ->addAction(
                WebDriver_Server_Selendroid::ACTION_POINTER_DOWN,
                $this->getCoordParams($elementRef, $x, $y)
            );
    }


    /**
     * Puts pointer up.
     *
     * @return $this
     */
    public function up()
    {
        return $this
            ->switchIsDown(false)
            ->addAction(WebDriver_Server_Selendroid::ACTION_POINTER_UP);
    }


    /**
     * Moves pointer (it must be in "down" state).
     *
     * @param array|null $elementRef
     * @param int $x
     * @param int $y
     * @return $this
     * @throws WebDriver_Exception
     */
    public function move(array $elementRef = null, $x = 0, $y = 0)
    {
        return $this
            ->assertIsDown()
            ->addAction(
                WebDriver_Server_Selendroid::ACTION_POINTER_MOVE,
                $this->getCoordParams($elementRef, $x, $y)
            );
    }


    /**
     * Pauses chain execution.
     *
     * @param int $ms
     * @return WebDriver_Server_Selendroid_Touch_ActionBuilder
     */
    public function pause($ms = 0)
    {
        return $this
            ->addAction(
                WebDriver_Server_Selendroid::ACTION_PAUSE,
                ['ms' => (int) $ms]
            );
    }


    /**
     * Flicks screen (it should be in "up" state). This action should not be performed in multi-actions.
     *
     * @param int $x
     * @param int $y
     * @param string $direction
     * @param int $distance
     * @param int $duration
     * @return $this
     * @throws WebDriver_Exception
     */
    public function flick($x, $y, $direction, $distance, $duration)
    {
        return $this
            ->assertIsDown(false)
            ->addAction(
                WebDriver_Server_Selendroid::ACTION_FLICK,
                [
                    'x' => (int) $x,
                    'y' => (int) $y,
                    'direction' => (string) $direction,
                    'distance' => (int) $distance,
                    'duration' => (int) $duration,
                ]
            );
    }


    /**
     * Flicks screen up.
     *
     * @param int $x
     * @param int $y
     * @param int $distance
     * @param int $duration
     * @return $this
     */
    public function flickUp($x, $y, $distance, $duration)
    {
        return $this
            ->flick($x, $y, WebDriver_Server_Selendroid::DIRECTION_UP, $distance, $duration);
    }


    /**
     * Flicks screen down.
     *
     * @param int $x
     * @param int $y
     * @param int $distance
     * @param int $duration
     * @return $this
     */
    public function flickDown($x, $y, $distance, $duration)
    {
        return $this
            ->flick($x, $y, WebDriver_Server_Selendroid::DIRECTION_DOWN, $distance, $duration);
    }


    /**
     * Flicks screen to the left.
     *
     * @param int $x
     * @param int $y
     * @param int $distance
     * @param int $duration
     * @return $this
     */
    public function flickLeft($x, $y, $distance, $duration)
    {
        return $this
            ->flick($x, $y, WebDriver_Server_Selendroid::DIRECTION_LEFT, $distance, $duration);
    }


    /**
     * Flicks screen to the right.
     *
     * @param int $x
     * @param int $y
     * @param int $distance
     * @param int $duration
     * @return $this
     */
    public function flickRight($x, $y, $distance, $duration)
    {
        return $this
            ->flick($x, $y, WebDriver_Server_Selendroid::DIRECTION_RIGHT, $distance, $duration);
    }


    /**
     * Cancels all pointer actions.
     *
     * @return $this
     */
    public function cancel()
    {
        $this->isDown = false;
        return $this
            ->addAction(WebDriver_Server_Selendroid::ACTION_POINTER_CANCEL);
    }


    /**
     * Returns action object with current action chain assigned.
     *
     * @return array
     */
    public function build()
    {
        return $this->actionChain;
    }


    /**
     * Builds flick that avoids "kick" in the end.
     *
     * @param array|null $elementRef
     * @param int $x
     * @param int $y
     * @param string $direction
     * @param int $distance
     * @return $this
     */
    public function slowFlick(array $elementRef = null, $x, $y, $direction, $distance)
    {
        // Step size is empiric, it probably can depend on screen resolution or size.
        $stepSize = 30;
        $stepCount = (int) ($distance - $distance % $stepSize) / $stepSize;
        $this
            ->down($elementRef, $x, $y);
        list($xMultiplier, $yMultiplier) = $this->getDirectionMultipliers($direction);
        // Moving by small steps with zero pauses.
        for ($i = 1; $i <= $stepCount; $i++) {
            $offset = $stepSize * $i;
            $this
                ->move($elementRef, $x + $offset * $xMultiplier, $y + $offset * $yMultiplier)
                ->pause();
        }
        // Pause lengths before and after final step are empiric, too.
        $this
            ->pause(100)
            ->move($elementRef, $x + $distance * $xMultiplier, $y + $distance * $yMultiplier)
            ->pause(100)
            ->up();
        // Precision of scrolling is not perfect, errors of several pixels are possible.
        return $this;
    }


    protected function getDirectionMultipliers($direction)
    {
        if (!isset($this->directionList[$direction])) {
            throw new WebDriver_Exception("Invalid flick direction: {$direction}");
        }
        return $this->directionList[$direction];
    }
}
