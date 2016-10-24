<?php

class WebDriver_Server_Selendroid_Touch extends WebDriver_Touch
{

    /**
     * @var WebDriver_Server_Selendroid
     */
    protected $webDriver = null;


    public function __construct(WebDriver_Server_Selendroid $webDriver)
    {
        parent::__construct($webDriver);
    }


    /**
     * @return WebDriver_Server_Selendroid_Touch_ActionBuilder
     */
    public function actionBuilder()
    {
        return new WebDriver_Server_Selendroid_Touch_ActionBuilder();
    }


    /**
     * @param array $action
     * @return $this
     * @throws WebDriver_Exception
     */
    public function performAction(array $action)
    {
        return $this
            ->performMultipleActions([$action]);
    }


    public function performMultipleActions(array $actionList)
    {
        $payload = [];
        foreach ($actionList as $action) {
            $payload[] = $this->buildActionChain($action);
        }
        $this->webDriver->getDriver()->curl(
            $this->webDriver->getDriver()->factoryCommand(
                'actions',
                WebDriver_Command::METHOD_POST,
                [
                    'payload' => $payload,
                ]
            )
        );
        return $this;
    }


    protected function buildActionChain(array $action)
    {
        return [
            'inputDevice' => 'touch',
            'id' => '',
            'actions' => $action,
        ];
    }
}
