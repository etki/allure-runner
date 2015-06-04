<?php

namespace Etki\Testing\AllureFramework\Runner\Exception\Utility\Filesystem;

use Etki\Testing\AllureFramework\Runner\Exception\BadMethodCallException;
use Exception;

/**
 * This exception is designed to be thrown in case unregistered node is
 * requested through TemporaryNodesManager.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Exception\Utility\Filesystem
 * @author  Etki <etki@etki.name>
 */
class NonexistentTemporaryNodeException extends BadMethodCallException
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
     * @since 0.1.0
     */
    public function __construct(
        $message = '',
        $code = 0,
        Exception $previous = null
    ) {
        if (!$message) {
            $message = 'Tried to use nonexistent node';
        }
        parent::__construct($message, $code, $previous);
    }
}
