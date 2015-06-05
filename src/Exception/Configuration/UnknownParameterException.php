<?php

namespace Etki\Testing\AllureFramework\Runner\Exception\Configuration;

/**
 * Designed to be thrown when unknown parameter is provided.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Exception\Configuration
 * @author  Etki <etki@etki.name>
 */
class UnknownParameterException extends InvalidConfigurationException
{
    /**
     * Retrieves default message.
     *
     * @param string $parameter Parameter name.
     *
     * @codeCoverageIgnore
     *
     * todo LSP violation
     *
     * @return string
     * @since 0.1.0
     */
    public static function getDefaultMessage($parameter)
    {
        $message = sprintf(
            'Received parameter `%s` doesn\'t exist within configuration',
            $parameter
        );
        return $message;
    }
}
