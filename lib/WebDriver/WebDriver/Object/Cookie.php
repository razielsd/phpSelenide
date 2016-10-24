<?php
class WebDriver_Object_Cookie extends WebDriver_Object
{

    /**
     * Retrieve one cookie visible to the current page.
     */
    public function get($name)
    {
        $command = $this->driver->factoryCommand('cookie', WebDriver_Command::METHOD_GET);
        $value = $this->driver->curl($command)['value'];
        $search = array_filter(
            $value,
            function ($item) use ($name) {
                return ($item['name'] == $name);
            }
        );
        $search = array_values($search);
        $value = empty($search)?null:$search[0];
        if ($value) {
            $value = new WebDriver_Object_Cookie_CookieInfo($value);
        }
        return $value;
    }


    /**
     * Retrieve all cookies visible to the current page.
     */
    public function getAll()
    {
        $command = $this->driver->factoryCommand('cookie', WebDriver_Command::METHOD_GET);
        $all = $this->driver->curl($command)['value'];
        $result = [];
        foreach ($all as $value) {
            $result[$value['value']] = new WebDriver_Object_Cookie_CookieInfo($value);
        }
        return $result;
    }


    /**
     * Set a cookie.
     *
     * @todo add parameters: path, domain, secure, expiry
     *
     * @param $name
     * @param $value
     */
    public function set($name, $value, $secure=false)
    {
        $params = ['cookie' =>
            [
                'name' => $name,
                'value' => "{$value}",
                'secure' => $secure,
            ]
        ];
        $command = $this->driver->factoryCommand('cookie', WebDriver_Command::METHOD_POST, $params);
        $this->driver->curl($command);
    }


    /**
     * Delete the cookie with the given name.
     *
     * @param $name
     */
    public function delete($name)
    {
        $command = $this->driver->factoryCommand("cookie/{$name}", WebDriver_Command::METHOD_DELETE);
        $this->driver->curl($command);
    }


    /**
     * Delete all cookies visible to the current page.
     */
    public function clearAll()
    {
        $command = $this->driver->factoryCommand('cookie', WebDriver_Command::METHOD_DELETE);
        $this->driver->curl($command);
    }
}
