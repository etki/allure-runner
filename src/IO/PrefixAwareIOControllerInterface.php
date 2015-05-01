<?php

namespace Etki\Testing\AllureFramework\Runner\IO;

/**
 * This interface describes IOController capable of line-prefixing.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\IO
 * @author  Etki <etki@etki.name>
 */
interface PrefixAwareIOControllerInterface
{
    /**
     * Constant for full-blown prefix format.
     *
     * @since 0.1.0
     */
    const FULL_PREFIX_FORMAT = '{dateTime} {software} {level}';
    /**
     * Constant for medium-length prefix format.
     *
     * @since 0.1.0
     */
    const MEDIUM_PREFIX_FORMAT = '{time} {softwareName} {level}';
    /**
     * Constant for tiny prefix format.
     *
     * @since 0.1.0
     */
    const SHORT_PREFIX_FORMAT = '{time}';
    /**
     * Sets new prefix format.
     *
     * @param string $prefixFormat New prefix format.
     *
     * @return void
     * @since 0.1.0
     */
    public function setPrefixFormat($prefixFormat);
}
