<?php

namespace Etki\Testing\AllureFramework\Runner\Exception\AllureCli;

use Etki\Testing\AllureFramework\Runner\Exception\RuntimeException;
use Exception;

/**
 * Exception designed to be thrown in case of missing executable in command
 * builder.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Exception\AllureCli
 * @author  Etki <etki@etki.name>
 */
class ExecutableNotSpecifiedException extends RuntimeException
{
    /**
     * Specifies default exception message.
     *
     * @codeCoverageIgnore
     *
     * @return string
     * @since 0.1.0
     */
    public static function getDefaultMessage()
    {
        return 'Couldn\'t create command due to missing executable';
    }
}
