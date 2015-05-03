<?php

namespace Etki\Testing\AllureFramework\Runner\Exception;

use RuntimeException as SplRuntimeException;

/**
 * Base runtime exception.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Exception
 * @author  Etki <etki@etki.name>
 */
class RuntimeException extends SplRuntimeException implements
    AllureRunnerExceptionInterface
{
}
