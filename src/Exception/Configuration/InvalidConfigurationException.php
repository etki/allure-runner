<?php

namespace Etki\Testing\AllureFramework\Runner\Exception\Configuration;

use Etki\Testing\AllureFramework\Runner\Exception\LogicException;

/**
 * Designed to be thrown whenever bad configuration is passed in.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Exception\Configuration
 * @author  Etki <etki@etki.name>
 */
class InvalidConfigurationException extends LogicException
{
    /**
     * Default exception message.
     *
     * @return string
     * @since 0.1.0
     */
    public static function getDefaultMessage()
    {
        return 'Provided configuration has failed validation';
    }
}
