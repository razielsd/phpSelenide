<?php
class WebDriver_Object_Frame extends WebDriver_Object
{

    /**
     * Change focus to another frame on the page.
     * If the frame id is null, the server should switch to the page's default content.
     */
    public function focus($frameId=null)
    {
        if ($frameId instanceof WebDriver_Element) {
            $frameId = $frameId->getReference();
        }
        $params = [
            'id' => $frameId
        ];
        $command = $this->driver->factoryCommand(
            'frame',
            WebDriver_Command::METHOD_POST,
            $params
        );
        return $this->driver->curl($command)['value'];
    }


    /**
     * Change focus to the parent context.
     * If the current context is the top level browsing context, the context remains unchanged.
     */
    public function parent()
    {
        $command = $this->driver->factoryCommand(
            'frame/parent',
            WebDriver_Command::METHOD_POST
        );
        return $this->driver->curl($command)['value'];

    }
}
