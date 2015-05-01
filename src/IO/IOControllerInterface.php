<?php

namespace Etki\Testing\AllureFramework\Runner\IO;

use Etki\Testing\AllureFramework\Runner\Configuration\Verbosity;

/**
 * Unified IOController interface.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\IO
 * @author  Etki <etki@etki.name>
 */
interface IOControllerInterface
{
    /**
     * Sets verbosity level.
     *
     * @param int $verbosity Verbosity level.
     *
     * @return void
     * @since 0.1.0
     */
    public function setVerbosity($verbosity);

    /**
     * Outputs single message on the same line.
     *
     * @param string $message   Message to write.
     * @param string $verbosity Message verbosity level.
     *
     * @return void
     * @since 0.1.0
     */
    public function write($message, $verbosity = Verbosity::LEVEL_INFO);
    
    /**
     * Outputs message to IO controller interface.
     *
     * @param string $message   Message to push.
     * @param string $verbosity Verbosity level.
     *
     * @return void
     * @since 0.1.0
     */
    public function writeLine(
        $message = '',
        $verbosity = Verbosity::LEVEL_INFO
    );

    /**
     * Outputs several messages at once with same verbosity.
     *
     * @param string[] $messages  List of messages.
     * @param string   $verbosity Verbosity level.
     *
     * @return void
     * @since 0.1.0
     */
    public function writeLines(
        array $messages,
        $verbosity = Verbosity::LEVEL_INFO
    );
}
