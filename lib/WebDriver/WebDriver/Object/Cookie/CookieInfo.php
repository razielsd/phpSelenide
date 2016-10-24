<?php

/**
 * Cookie information
 *
 * @property $name
 * @property $secure
 * @property $value
 * @property $path
 * @property $httpOnly
 * @property $expired
 */
class WebDriver_Object_Cookie_CookieInfo
{
    protected $cookie = null;
    protected $propertyList = ['name' => 'name', 'domain' => 'domain',
        'secure' => 'secure',
        'value' => 'value',
        'path' => 'path',
        'httpOnly' => 'httpOnly',
        'expired' => 'expiry'
    ];

    public function __construct($info)
    {
        $this->cookie = $info;
    }


    public function __get($propertyName)
    {
        if (!isset($this->propertyList[$propertyName])) {
            throw new WebDriver_Exception("Call unknown propery '{$propertyName}' in " . static::class);
        }
        return $this->cookie[$this->propertyList[$propertyName]];
    }


    /**
     * Cast to cookie value
     *
     * @return string
     */
    public function __toString()
    {
        return $this->value;
    }
}