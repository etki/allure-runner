<?php

namespace Etki\Testing\AllureFramework\Runner\IO;

/**
 * Interface for writer that does nothing but pushes message to it's output.
 * Please note that implementation *may* be a simple wrapper around another
 * writer implementation, so additional formatting (like appending newlines to
 * every message) is possible.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\IO
 * @author  Etki <etki@etki.name>
 */
interface WriterInterface
{
    /**
     * Outputs message as is.
     *
     * @param string $message Message to write.
     *
     * @return void
     * @since 0.1.0
     */
    public function write($message);
}
