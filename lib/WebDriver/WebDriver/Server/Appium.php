<?php

class WebDriver_Server_Appium extends WebDriver
{


    /**
     * Get page element using normalized locator for xpath UTF strings
     *
     * @param $locator
     * @return WebDriver_Element
     */
    public function findByXpath($locator)
    {
        $locator = \Normalizer::normalize($locator, \Normalizer::FORM_D);
        return parent::find($locator);
    }


    /**
     * Получает дерево элементов от ios automation
     * @return array
     */
    public function getTree()
    {
        return $this->getDriver()->curl(
            $this->getDriver()->factoryCommand(
                'execute',
                \WebDriver_Command::METHOD_POST,
                ['script' => 'au.mainApp().getTreeForXML()', 'args' => []]
            )
        );
    }
}
