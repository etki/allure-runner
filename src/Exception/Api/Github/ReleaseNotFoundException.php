<?php

namespace Etki\Testing\AllureFramework\Runner\Exception\Api\Github;

use Etki\Testing\AllureFramework\Runner\Exception\RuntimeException;
use Exception;

/**
 * Designed to be thrown whenever release is not found.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Exception\Api\Github
 * @author  Etki <etki@etki.name>
 */
class ReleaseNotFoundException extends RuntimeException
{
    /**
     * Initializer.
     *
     * @param string    $message  Exception message.
     * @param int       $code     Exception code.
     * @param Exception $previous Previous exception.
     *
     * @codeCoverageIgnore
     *
     * @return self
     * @since 0.1.0
     */
    public function __construct(
        $message,
        $code = 0,
        Exception $previous = null
    ) {
        if (!$message) {
            $message = 'Could not find specified release';
        }
        parent::__construct($message, $code, $previous);
    }

    /**
     * Creates default message,
     *
     * @param string $tag Tag name,
     *
     * @codeCoverageIgnore
     *
     * @return string
     * @since 0.1.0
     */
    public static function createDefaultMessage($tag)
    {
        return sprintf('Release `%s` could not be found', $tag);
    }
}
