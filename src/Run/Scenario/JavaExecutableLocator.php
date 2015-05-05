<?php

namespace Etki\Testing\AllureFramework\Runner\Run\Scenario;

use Etki\Testing\AllureFramework\Runner\Configuration\Verbosity;
use Etki\Testing\AllureFramework\Runner\Environment\Filesystem\FileLocator;
use Etki\Testing\AllureFramework\Runner\IO\IOControllerInterface;

/**
 * Locates java executable (or doesn't).
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Run\Scenario
 * @author  Etki <etki@etki.name>
 */
class JavaExecutableLocator
{
    /**
     * File locator instance.
     *
     * @type FileLocator
     * @since 0.1.0
     */
    private $fileLocator;
    /**
     * I\O controller instance.
     *
     * @type IOControllerInterface
     * @since 0.1.0
     */
    private $ioController;

    /**
     * Initializer.
     *
     * @param FileLocator           $fileLocator  File locator.
     * @param IOControllerInterface $ioController I\O controller.
     *
     * @return self
     * @since 0.1.0
     */
    public function __construct(
        FileLocator $fileLocator,
        IOControllerInterface $ioController
    ) {
        $this->ioController = $ioController;
        $this->fileLocator = $fileLocator;
    }

    /**
     * Returns path to java executable.
     *
     * @return null|string
     * @since 0.1.0
     */
    public function getJavaExecutable()
    {
        $message = 'Searching for Java executable';
        $this->ioController->writeLine($message, Verbosity::LEVEL_NOTICE);
        if ($executables = $this->fileLocator->locateExecutable('java')) {
            $java = array_shift($executables);
            $message = sprintf('Found Java executable at %s', $java);
            $this->ioController->writeLine($message, Verbosity::LEVEL_NOTICE);
            return $java;
        }
        $message = 'Couldn\'t find Java executable';
        $this->ioController->writeLine($message, Verbosity::LEVEL_WARNING);
        return null;
    }
}
