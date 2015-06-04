<?php

namespace Etki\Testing\AllureFramework\Runner\AllureCli;

/**
 * Pushes out command builders.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\AllureCli
 * @author  Etki <etki@etki.name>
 */
class CommandBuilderFactory
{
    /**
     * Creates new command builder.
     *
     * @param string $executable Path to executable.
     * @param string $command    Command to run.
     *
     * @codeCoverageIgnore
     *
     * @return CommandBuilder Command builder instance.
     * @since 0.1.0
     */
    public function getCommandBuilder($executable = null, $command = null)
    {
        return new CommandBuilder($executable, $command);
    }
}
