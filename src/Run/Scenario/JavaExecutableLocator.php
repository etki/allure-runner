<?php

namespace Etki\Testing\AllureFramework\Runner\Run\Scenario;

use Etki\Testing\AllureFramework\Runner\Configuration\Verbosity;
use Etki\Testing\AllureFramework\Runner\Environment\Filesystem\FileLocatorFactory;
use Etki\Testing\AllureFramework\Runner\Environment\Filesystem\FileLocatorInterface;
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
     * @type FileLocatorInterface
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
     * @param FileLocatorFactory    $fileLocatorFactory File locator factory.
     * @param IOControllerInterface $ioController       I\O controller.
     *
     * @return self
     * @since 0.1.0
     */
    public function __construct(
        FileLocatorFactory $fileLocatorFactory,
        IOControllerInterface $ioController
    ) {
        $this->ioController = $ioController;
        $this->fileLocator = $fileLocatorFactory->getFileLocator();
    }

    /**
     * Returns path to java executable.
     *
     * @return null|string
     * @since 0.1.0
     */
    public function getJavaExecutable()
    {
        $message = 'Searching for java executable';
        $this->ioController->writeLine($message, Verbosity::LEVEL_NOTICE);
        if ($java = $this->fileLocator->locateExecutable('java')) {
            $message = sprintf('Found java executable at `%s`', $java);
            $this->ioController->writeLine($message, Verbosity::LEVEL_NOTICE);
            return $java;
        }
        $this->ioController->writeLine('Couldn\'t find java executable');
        return null;
    }
}
