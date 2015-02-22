<?php

namespace Etki\Testing\AllureFramework\Runner\Environment;

/**
 *
 *
 * @version 0.1.0
 * @since   
 * @package Etki\Testing\AllureFramework\Runner\Environment
 * @author  Etki <etki@etki.name>
 */
class Environment
{
    const FAMILY_MAC = 'mac';
    const FAMILY_WINDOWS = 'windows';
    const FAMILY_LINUX = 'linux';
    const FAMILY_UNIX = 'unix';
    private $family;
    public function __construct()
    {
        $uname = php_uname('s');
        if ($uname === 'Darwin') {
            $this->family = self::FAMILY_MAC;
        } elseif ($uname === 'Linux') {
            $this->family = self::FAMILY_LINUX;
        } elseif (substr(strtolower($uname), 0, 3) === 'win') {
            $this->family = self::FAMILY_WINDOWS;
        } else {
            // unix by default lol
            $this->family = self::FAMILY_UNIX;
        } 
    }
    public function getJavaExecutable()
    {
        $locator = new FileLocator($this->family);
        return $locator->locateExecutableFile('java');
    }
    public function getAllureJar()
    {
        $locator = new FileLocator($this->family);
        $path = $locator->locateFile('allure-cli.jar');
        if (!$path) {
            $path = $locator->locateFile('allure.jar');
        }
        return $path;
    }
    public function getAllureExecutable()
    {
        $locator = new FileLocator($this->family);
        if ($path = $locator->locateExecutableFile('allure-cli')) {
            return $path;
        }
        return $locator->locateExecutableFile('allure');
    }
}
