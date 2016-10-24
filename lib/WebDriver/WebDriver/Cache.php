<?php

/**
 * Context object allows to share states and resources across different elements.
 */
class WebDriver_Cache
{

    const RESOURCE_SCREENSHOT = 'screenshot';


    /**
     * @var WebDriver
     */
    protected $webDriver = null;

    /**
     * @var array
     */
    protected $resourceList = [
        self::RESOURCE_SCREENSHOT => false,
    ];

    protected $data = [];


    public function __construct(WebDriver $webDriver)
    {
        $this->webDriver = $webDriver;
    }


    public function __clone()
    {
        $this->data = [];
    }


    public function __destruct()
    {
        $this->reset();
    }


    public function get($resourceId)
    {
        $this->assertResourceId($resourceId);
        if (!$this->isEnabled($resourceId) || !isset($this->data[$resourceId])) {
            $this->data[$resourceId] = $this
                ->reset($resourceId)
                ->create($resourceId);
        }
        return $this->data[$resourceId];
    }


    public function reset($resourceId = null)
    {
        $resourceIdList = null === $resourceId ? array_keys($this->resourceList) : (array) $resourceId;
        foreach ($resourceIdList as $id) {
            $this->assertResourceId($id);
            if (isset($this->data[$id])) {
                $value = $this->data[$id];
                unset($this->data[$id]);
                switch ($id) {
                    case self::RESOURCE_SCREENSHOT:
                        $this->resetScreenshot($value);
                        break;
                }
            }
        }
        return $this;
    }


    public function create($resourceId)
    {
        $this->assertResourceId($resourceId);
        switch ($resourceId) {
            case self::RESOURCE_SCREENSHOT:
                return $this->createScreenshot();
        }
        throw new WebDriver_Exception("No create method defined for WebDriver cache resource ID: {$resourceId}");
    }


    public function enable($resourceId = null)
    {
        return $this->setEnabled(true, $resourceId);
    }


    public function disable($resourceId = null)
    {
        return $this->setEnabled(false, $resourceId);
    }


    public function isEnabled($resourceId)
    {
        return $this
            ->assertResourceId($resourceId)
            ->resourceList[$resourceId];
    }


    protected function setEnabled($on = true, $resourceId = null)
    {
        $resourceIdList = null === $resourceId ? array_keys($this->resourceList) : (array) $resourceId;
        foreach ($resourceIdList as $id) {
            $this
                ->assertResourceId($id)
                ->resourceList[$id] = (bool) $on;
        }
        return $this;
    }


    protected function assertResourceId($resourceId)
    {
        if (!isset($this->resourceList[$resourceId])) {
            throw new WebDriver_Exception("Invalid WebDriver cache resource ID: {$resourceId}");
        }
        return $this;
    }


    /**
     * Takes screenshot and returns image resource.
     *
     * @return resource
     * @throws WebDriver_Exception
     */
    protected function createScreenshot()
    {
        $image = imagecreatefromstring($this->webDriver->screenshotAsImage());
        if (false === $image) {
            throw new WebDriver_Exception("Invalid screenshot data");
        }
        return $image;
    }


    /**
     * Resets screenshot.
     *
     * @return $this
     */
    protected function resetScreenshot($image)
    {
        if (is_resource($image)) {
            imagedestroy($image);
        }
        return $this;
    }
}
