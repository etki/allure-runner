<?php

namespace Etki\Testing\AllureFramework\Runner\IO\Controller;

use Etki\Testing\AllureFramework\Runner\Configuration\Verbosity;
use Etki\Testing\AllureFramework\Runner\IO\IOControllerInterface;

/**
 * Dummy I/O controller implementation. Acts like a black hole.
 *
 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
 *
 * @codeCoverageIgnore
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\IO\Controller
 * @author  Etki <etki@etki.name>
 */
class DummyController implements IOControllerInterface
{
    /**
     * Dummy implementation.
     *
     * @param string $message   Message to output (that won't happen).
     * @param string $verbosity Message verbosity level (who cares).
     *
     * @return void
     * @since 0.1.0
     */
    public function write($message, $verbosity = Verbosity::LEVEL_INFO)
    {
        // pass
    }

    /**
     * Dummy implementation.
     *
     * @param string $message   Message to output (that won't happen).
     * @param string $verbosity Message verbosity level (who cares).
     *
     * @return void
     * @since 0.1.0
     */
    public function writeLine($message = '', $verbosity = Verbosity::LEVEL_INFO)
    {
        // pass
    }

    /**
     * Dummy implementation.
     *
     * @param string[] $messages  Messages to output (that won't happen).
     * @param string   $verbosity Messages verbosity level (who cares).
     *
     * @return void
     * @since 0.1.0
     */
    public function writeLines(
        array $messages,
        $verbosity = Verbosity::LEVEL_INFO
    ) {
        // pass
    }

    /**
     * Dummy implementation
     *
     * @param int $verbosity Verbosity level.
     *
     * @return void
     * @since 0.1.0
     */
    public function setVerbosity($verbosity)
    {
        // pass
    }
}
