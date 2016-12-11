<?php
namespace Selenide;


class SelenideElement
{
    /**
     * @var \WebDriver_Element
     */
    protected $wdElement = null;
    /**
     * @var Selenide
     */
    protected $selenide = null;


    public function __construct(Selenide $selenide, \WebDriver_Element $element)
    {
        $this->selenide = $selenide;
        $this->wdElement = $element;
    }


    /**
     * Set element value
     *
     * @param $value
     * @return $this
     */
    public function setValue($value)
    {
        $element = $this->getElement();
        $element->value($value);
        return $this;
    }


    /**
     * Press key enter
     *
     * @return $this
     */
    public function pressEnter()
    {
        $driver = $this->selenide->getDriver()->webDriver();
        $element = $this->getElement();
        $element->keys([$driver::KEY_ENTER]);
        return $this;
    }


    /**
     * Click on element
     *
     * @return $this
     */
    public function click()
    {
        $element = $this->getElement();
        $element->click();
        return $this;
    }


    /**
     * Click on element
     *
     * @return $this
     */
    public function doubleClick()
    {
        $element = $this->getElement();
        $element->dbclick();
        return $this;
    }


    /**
     * Select option by option text
     *
     * @param $text
     * @return $this
     */
    public function selectOption($text)
    {
        throw new Exception(__METHOD__ . ' - under construction');
        //$element = $this->getElement();
        //$this->selenide->getReport()->addChildEvent('selectOption');
        //$element->value();
        return $this;
    }


    /**
     * Select option by value
     *
     * @param $value
     * @return $this
     */
    public function selectOptionByValue($value)
    {
        $element = $this->getElement();
        $element->value($value);
        return $this;
    }


    /**
     * Get element value
     *
     * @return string
     */
    public function val()
    {
        $element = $this->getElement();
        return $element->value();
    }


    /**
     * Get element attribute
     *
     * @param $name
     * @return string
     *
     * @throws Exception_ElementNotFound
     */
    public function attribute($name)
    {
        $element = $this->getElement();
        $attrValue =  $element->attribute($name);

        if ($attrValue === null) {
            throw new Exception_ElementNotFound('Attribute ' . $name . ' not found');
        }
        return $attrValue;
    }


    /**
     * Get element text
     *
     * @return string
     */
    public function text()
    {
        $element = $this->getElement();
        $this->selenide->getReport()->addChildEvent('Get element text');
        return $element->text();
    }


    /**
     * Check exists element
     *
     * @return bool
     */
    public function exists()
    {
        try {
            $element = $this->getElement();
        } catch (\WebDriver_Exception_FailedCommand $e) {
            $element = null;
        }
        return !is_null($element);
    }


    /**
     * Check checkbox checked
     *
     * @return bool
     */
    public function checked()
    {
        $element = $this->getElement();
        return $element->checked();
    }


    /**
     * Check element visible
     *
     * @return bool
     */
    public function isDisplayed()
    {
        try {
            $element = $this->getElement();
        } catch (Exception_ElementNotFound $e) {
            return false;
        }
        return $element->isDisplayed();
    }


    /**
     * Get WebDriver elementId, can be changed after refresh page or recreate
     *
     * @return int
     */
    public function getElementId()
    {
        return $this->getElement()->getElementId();
    }


    
    /**
     * @return \WebDriver_Element
     */
    protected function getElement()
    {
        return $this->wdElement;
    }
}