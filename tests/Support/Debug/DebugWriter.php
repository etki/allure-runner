<?php

namespace Etki\Testing\AllureFramework\Runner\Tests\Support\Debug;

use Codeception\Util\Debug;
use Etki\Testing\AllureFramework\Runner\IO\WriterInterface;

/**
 * Writer that simply passes everything to codeception debug stream.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Tests\Support\Debug
 * @author  Etki <etki@etki.name>
 */
class DebugWriter implements WriterInterface
{
    /**
     * Writes single message.
     *
     * @param string $message Message to write.
     *
     * @return void
     * @since 0.1.0
     */
    public function write($message)
    {
        Debug::debug($message);
    }
}
