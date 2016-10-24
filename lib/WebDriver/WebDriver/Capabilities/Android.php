<?php

/**
 * @property string $version
 * @property string $aut
 * @property string $launchActivity
 * @property bool $emulator
 * @property string $serial
 */
class WebDriver_Capabilities_Android extends WebDriver_Capabilities
{

    protected $data = [
        'version' => null,
        'aut' => null,
        'launchActivity' => null,
        'emulator' => false,
        'serial' => null,
        'rotatable' => true,
        'preSessionAdbCommands' => null,
    ];
}
