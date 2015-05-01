<?php

namespace Etki\Testing\AllureFramework\Runner\Environment\Filesystem;

/**
 * Generic file locator for UNIX-base systems.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Environment\Filesystem
 * @author  Etki <etki@etki.name>
 */
class UnixFileLocator extends AbstractFileLocator
{
    /**
     * Template for locate file command.
     *
     * @since 0.1.0
     */
    const COMMAND_TEMPLATE_LOCATE = 'locate %s';
    /**
     * Template for simple find command.
     *
     * @since 0.1.0
     */
    const COMMAND_TEMPLATE_FIND = 'find / -name %s';
    /**
     * Template for which command.
     *
     * @since 0.1.0
     */
    const COMMAND_TEMPLATE_WHICH = 'which %s';

    /**
     * Locates executable file.
     *
     * @param string $name Executable file name.
     *
     * @return string|null
     * @since 0.1.0
     */
    public function locateExecutable($name)
    {
        $command = sprintf(self::COMMAND_TEMPLATE_WHICH, $name);
        $process = $this->getProcessFactory()->getProcess($command);
        $process->run();
        if ($process->run() !== 0 || !trim($process->getOutput())) {
            return null;
        }
        $lines = explode("\n", trim($process->getOutput()));
        // getting only first line for now
        $result = array_shift($lines);
        if (!$result) {
            return null;
        }
        return $result;
    }

    /**
     * Locates file by it's name.
     *
     * @param string $name Name of the file.
     *
     * @return string|null Path to file on success or null
     * @since 0.1.0
     */
    public function locateFile($name)
    {
        $command = sprintf(self::COMMAND_TEMPLATE_LOCATE, $name);
        $process = $this->getProcessFactory()->getProcess($command);
        if ($process->run() !== 0 || !trim($process->getOutput())) {
            return null;
        }
        $lines = explode("\n", trim($process->getOutput()));
        // getting only first line for now
        $result = array_shift($lines);
        if (!$result) {
            return null;
        }
        return $result;
    }
}
