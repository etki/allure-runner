<?php

namespace Etki\Testing\AllureFramework\Runner\Environment\Filesystem;

/**
 * File locator for Mac OS.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Environment\Filesystem
 * @author  Etki <etki@etki.name>
 */
class MacOsFileLocator extends UnixFileLocator
{
    /**
     * Command template for locating single file.
     *
     * @since 0.1.0
     */
    const COMMAND_TEMPLATE_MDFIND = 'mdfind %s';

    /**
     * Locates file.
     *
     * @param string $name Name of the file to find.
     *
     * @return string|null Path to file.
     * @since 0.1.0
     */
    public function locateFile($name)
    {
        if ($result = parent::locateFile($name)) {
            return $result;
        }
        $command = sprintf(self::COMMAND_TEMPLATE_MDFIND, $name);
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
