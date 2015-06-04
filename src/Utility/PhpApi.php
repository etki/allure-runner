<?php

namespace Etki\Testing\AllureFramework\Runner\Utility;

/**
 * This class wraps some basic PHP function so they can be easily mocked.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Utility
 * @author  Etki <etki@etki.name>
 */
class PhpApi
{
    /**
     * Mode for outputting architecture.
     *
     * @since 0.1.0
     */
    const UNAME_MODE_ARCHITECTURE = 'm';
    /**
     * Mode for outputting operating system version.
     *
     * @since 0.1.0
     */
    const UNAME_MODE_VERSION = 'v';
    /**
     * Mode for outputting operating system release number.
     *
     * @since 0.1.0
     */
    const UNAME_MODE_RELEASE_NUMBER = 'r';
    /**
     * Mode for outputting host name.
     *
     * @since 0.1.0
     */
    const UNAME_MODE_HOST_NAME = 'n';
    /**
     * Mode for outputting system name.
     *
     * @since 0.1.0
     */
    const UNAME_MODE_OPERATING_SYSTEM_NAME = 's';
    /**
     * Mode for outputting full uname string.
     *
     * @since 0.1.0
     */
    const UNAME_MODE_ALL = 'a';
    
    /**
     * `php_uname()` wrapper.
     *
     * @param string $mode Mode to use.
     *
     * @codeCoverageIgnore
     *
     * @return string
     * @since 0.1.0
     */
    public function uname($mode = null)
    {
        return php_uname($mode);
    }

    /**
     * Returns current time in seconds.
     *
     * @codeCoverageIgnore
     *
     * @return float
     * @since 0.1.0
     */
    public function getTime()
    {
        return microtime(true);
    }
}
