<?php

namespace Etki\Testing\AllureFramework\Runner\Exception;

use LogicException as SplLogicException;

/**
 * Base logic exception.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Exception
 * @author  Etki <etki@etki.name>
 */
class LogicException extends SplLogicException implements
    AllureRunnerExceptionInterface
{
}
