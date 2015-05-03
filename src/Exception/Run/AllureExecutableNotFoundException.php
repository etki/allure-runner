<?php

namespace Etki\Testing\AllureFramework\Runner\Exception\Run;

use Etki\Testing\AllureFramework\Runner\Exception\RuntimeException;
use Exception;

/**
 * Designed to be thrown whenever executable for allure could not be found.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Exception\Run
 * @author  Etki <etki@etki.name>
 */
class AllureExecutableNotFoundException extends RuntimeException
{
    /**
     * Initializer.
     *
     * @param string    $message  Exception message.
     * @param int       $code     Exception code.
     * @param Exception $previous Previous exception.
     *
     * @return self
     * @since 0.1.0
     */
    public function __construct(
        $message = '',
        $code = 0,
        Exception $previous = null
    ) {
        if (!$message) {
            $message = 'Couldn\'t locate Allure CLI executable';
        }
        parent::__construct($message, $code, $previous);
    }
}
