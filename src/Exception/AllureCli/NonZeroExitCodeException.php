<?php

namespace Etki\Testing\AllureFramework\Runner\Exception\AllureCli;

use Etki\Testing\AllureFramework\Runner\Exception\RuntimeException;

/**
 * Exception to be thrown on non-zero exit code result.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Exception\AllureCli
 * @author  Etki <etki@etki.name>
 */
class NonZeroExitCodeException extends RuntimeException
{
    /**
     * Returns default exception message
     *
     * @param int $exitCode Allure exit code.
     *
     * @codeCoverageIgnore
     *
     * @return string
     * @since 0.1.0
     */
    public static function getDefaultMessage($exitCode)
    {
        $message = sprintf(
            'Allure run has finished with exit code other than zero (%d)',
            $exitCode
        );
        return $message;
    }
}
