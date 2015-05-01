<?php

namespace Etki\Testing\AllureFramework\Runner\Environment;

use Etki\Testing\AllureFramework\Runner\Utility\PhpApi;

/**
 * This class helps in interaction with current OS and runtime.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Environment
 * @author  Etki <etki@etki.name>
 */
final class Runtime
{
    /**
     * MAC os family.
     *
     * @since 0.1.0
     */
    const FAMILY_MAC = 'mac';
    /**
     * Windows family.
     *
     * @since 0.1.0
     */
    const FAMILY_WINDOWS = 'windows';
    /**
     * Linux OS family.
     *
     * @since 0.1.0
     */
    const FAMILY_LINUX = 'linux';
    /**
     * UNIX OS family. Yes, distinguished from linux -_-.
     *
     * @since 0.1.0
     */
    const FAMILY_UNIX = 'unix';
    /**
     * Family name.
     *
     * @type string
     * @since 0.1.0
     */
    private $family;
    /**
     * PhpApi class instance.
     *
     * @type PhpApi
     * @since 0.1.0
     */
    private $phpApi;

    /**
     * Initializer.
     *
     * @param PhpApi $phpApi PHP API instance.
     *
     * @since 0.1.0
     */
    public function __construct(PhpApi $phpApi)
    {
        $this->phpApi = $phpApi;
    }

    /**
     * Detects operating system family.
     *
     * @return string Operating system family name.
     * @since 0.1.0
     */
    private function detectOsFamily()
    {
        $uname = $this->phpApi->uname(PhpApi::UNAME_OPERATING_SYSTEM_NAME_MODE);
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
        return $this->family;
    }

    /**
     * Retrieves OS family.
     *
     * @return string
     * @since 0.1.0
     */
    public function getOsFamily()
    {
        if (!isset($this->family)) {
            $this->detectOsFamily();
        }
        return $this->family;
    }
}
