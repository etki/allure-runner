<?php

namespace Etki\Testing\AllureFramework\Runner\Exception;

/**
 * Dummy exception to fill out the places where functionality hasn't been
 * implemented.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Exception
 * @author  Etki <etki@etki.name>
 */
class NotImplementedException extends LogicException
    implements AllureRunnerExceptionInterface
{
}
