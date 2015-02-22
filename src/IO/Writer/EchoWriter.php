<?php

namespace Etki\Testing\AllureFramework\Runner\IO\Writer;

use Etki\Testing\AllureFramework\Runner\IO\WriterInterface;

/**
 * Simple echo-based writer.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\IO\Writer
 * @author  Etki <etki@etki.name>
 */
class EchoWriter implements WriterInterface
{
    /**
     * Outputs message.
     *
     * @param string $message Message to write.
     *
     * @codeCoverageIgnore
     *
     * @return void
     * @since 0.1.0
     */
    public function write($message)
    {
        echo $message;
    }
}
