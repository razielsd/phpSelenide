<?php
/**
 * @property string $deviceName
 * @property string $version
 * @property string $platformName
 * @property string $app
 */
class WebDriver_Capabilities_iOS extends WebDriver_Capabilities
{
    protected $data = [
        'deviceName' => "iPhone 5s",
        'version' => "9.3",
        'platformName' => "iOS",
        'platformVersion' => "9.3",
        'launchTimeout' => 120000,
        'newCommandTimeout' => 120000,
        //'waitForAppScript' => '$.delay(5000); true;',
    ];
}
