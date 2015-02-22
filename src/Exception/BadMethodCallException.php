<?php

namespace Etki\Testing\AllureFramework\Runner\Exception;

use BadMethodCallException as SplBadMethodCallException;

/**
 * Base bad method call exception.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Exception
 * @author  Etki <etki@etki.name>
 */
class BadMethodCallException extends SplBadMethodCallException implements
    AllureRunnerException
{
}
