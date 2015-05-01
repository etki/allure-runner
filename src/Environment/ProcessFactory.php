<?php

namespace Etki\Testing\AllureFramework\Runner\Environment;

use Symfony\Component\Process\Process;

/**
 * Simple process factory. Just for mocking.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Environment
 * @author  Etki <etki@etki.name>
 */
class ProcessFactory
{
    /**
     * Returns new process instance.
     *
     * @param string $command Command to be run by process.
     *
     * @codeCoverageIgnore
     *
     * @return Process
     * @since 0.1.0
     */
    public function getProcess($command)
    {
        return new Process($command);
    }
}
