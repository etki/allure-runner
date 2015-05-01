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
    const UNAME_ARCHITECTURE_MODE = 'm';
    /**
     * Mode for outputting operating system version.
     *
     * @since 0.1.0
     */
    const UNAME_VERSION_MODE = 'v';
    /**
     * Mode for outputting operating system release number.
     *
     * @since 0.1.0
     */
    const UNAME_RELEASE_NUMBER_MODE = 'r';
    /**
     * Mode for outputting host name.
     *
     * @since 0.1.0
     */
    const UNAME_HOST_NAME_MODE = 'n';
    /**
     * Mode for outputting system name.
     *
     * @since 0.1.0
     */
    const UNAME_OPERATING_SYSTEM_NAME_MODE = 's';
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
     * Returns path for system temporary files folder.
     *
     * @codeCoverageIgnore
     *
     * @return string
     * @since 0.1.0
     */
    public function getSystemTemporaryFilesDirectory()
    {
        return sys_get_temp_dir();
    }
}
