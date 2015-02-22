<?php

namespace Etki\Testing\AllureFramework\Runner\Environment;

/**
 *
 *
 * @version 0.1.0
 * @since   
 * @package Etki\Testing\AllureFramework\Runner\Environment
 * @author  Etki <etki@etki.name>
 */
class Shell
{
    /**
     * Executes command.
     *
     * @param string $command Command to execute.
     *
     * @return void
     * @since 0.1.0
     */
    public function execute($command)
    {
        exec($command, $output, $exitCode);
    }
}
