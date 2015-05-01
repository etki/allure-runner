<?php

namespace Etki\Testing\AllureFramework\Runner\Environment\Filesystem;

use Etki\Testing\AllureFramework\Runner\Exception\NotImplementedException;

/**
 *
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Environment\Filesystem
 * @author  Etki <etki@etki.name>
 */
class WindowsFileLocator extends AbstractFileLocator
{
    /**
     * Template for executable search command.
     *
     * @since 0.1.0
     */
    const COMMAND_TEMPLATE_WHERE = 'where %s';

    /**
     * {@inheritdoc}
     *
     * @param string $name File name.
     *
     * @throws NotImplementedException Thrown because windows doesn't have an
     *                                 option for fast file location.
     *
     * @codeCoverageIgnore
     *
     * @return string|null Actually, will never return anything because of
     * Windows limitations.
     * @since 0.1.0
     */
    public function locateFile($name)
    {
        $message = sprintf(
            'Sadly, windows platform doesn\'t provide command-line indexed ' .
            'file search ' . 'option like UNIX `locate` command from command ' .
            'line, so Allure Runner can\'t find file `%s` in reasonable time',
            $name
        );
        throw new NotImplementedException($message);
    }

    /**
     * Locates executable by name.
     *
     * @param string $name Name of the executable.
     *
     * @return null|string Null or path to executable.
     * @since 0.1.0
     */
    public function locateExecutable($name)
    {
        $command = sprintf(self::COMMAND_TEMPLATE_WHERE, $name);
        $process = $this->getProcessFactory()->getProcess($command);
        if ($process->run() !== 0 || !trim($process->getOutput())) {
            return null;
        }
        $lines = explode("\n", trim($process->getOutput()));
        // using only first result for now
        $result = trim(array_shift($lines));
        if (!$result) {
            return null;
        }
        return $result;
    }
}
