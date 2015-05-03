<?php

namespace Etki\Testing\AllureFramework\Runner\Run\Scenario;

use Etki\Testing\AllureFramework\Runner\Configuration\Configuration;
use Etki\Testing\AllureFramework\Runner\Configuration\Verbosity;
use Etki\Testing\AllureFramework\Runner\Environment\Filesystem\FileLocatorFactory;
use Etki\Testing\AllureFramework\Runner\Environment\Filesystem\FileLocatorInterface;
use Etki\Testing\AllureFramework\Runner\IO\IOControllerInterface;

/**
 * Locates Allure executable (or doesn't).
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Run\Scenario
 * @author  Etki <etki@etki.name>
 */
class AllureExecutableResolver
{
    /**
     * Configuration instance.
     *
     * @type Configuration
     * @since 0.1.0
     */
    private $configuration;
    /**
     * I\O controller interface.
     *
     * @type IOControllerInterface
     * @since 0.1.0
     */
    private $ioController;
    /**
     * File locator.
     *
     * @type FileLocatorInterface
     * @since 0.1.0
     */
    private $fileLocator;
    /**
     * Java executable locator.
     *
     * @type JavaExecutableLocator
     * @since 0.1.0
     */
    private $javaLocator;
    /**
     * Jar file resolver.
     *
     * @type JarResolver
     * @since 0.1.0
     */
    private $jarResolver;
    
    /**
     * Initializer.
     *
     * @param Configuration         $configuration      Run configuration.
     * @param FileLocatorFactory    $fileLocatorFactory File locator for current
     *                                                  OS.
     * @param JavaExecutableLocator $javaLocator        Java executable locator.
     * @param JarResolver           $jarResolver        Allure `.jar` file
     *                                                  resolver.
     * @param IOControllerInterface $ioController       I\O controller instance.
     *
     * @return self
     * @since 0.1.0
     */
    public function __construct(
        Configuration $configuration,
        FileLocatorFactory $fileLocatorFactory,
        JavaExecutableLocator $javaLocator,
        JarResolver $jarResolver,
        IOControllerInterface $ioController
    ) {
        $this->configuration = $configuration;
        $this->fileLocator = $fileLocatorFactory->getFileLocator();
        $this->javaLocator = $javaLocator;
        $this->jarResolver = $jarResolver;
        $this->ioController = $ioController;
    }

    /**
     * Retrieves Allure executable.
     *
     * @return string|null
     * @since 0.1.0
     */
    public function getAllureExecutable()
    {
        if ($executable = $this->getConfigurationExecutable()) {
            return $executable;
        }
        if ($executable = $this->getGenericExecutable()) {
            return $executable;
        }
        if ($executable = $this->getJarLauncher()) {
            return $executable;
        }
        $message = 'Failed to find both Allure executable file and Java ' .
            'executable and/or Allure `.jar` file';
        $this->ioController->writeLine($message, Verbosity::LEVEL_ERROR);
        return null;
    }

    /**
     * Creates jar launcher command or returns null.
     *
     * @return null|string
     * @since 0.1.0
     */
    private function getJarLauncher()
    {
        $javaExecutable = $this->javaLocator->getJavaExecutable();
        if (!$javaExecutable) {
            $message = 'Failed to find Java executable';
            $this->ioController->writeLine($message, Verbosity::LEVEL_WARNING);
            return null;
        }
        $jarLocation = $this->jarResolver->resolveJar();
        if (!$jarLocation) {
            $message = 'Failed to find Allure `jar` file';
            $this->ioController->writeLine($message, Verbosity::LEVEL_WARNING);
            return null;
        }
        return sprintf('%s -jar %s', $javaExecutable, $jarLocation);
    }

    /**
     * Retrieves executable specified in configuration,
     *
     * @return null|string
     * @since 0.1.0
     */
    private function getConfigurationExecutable()
    {
        $executable = $this->configuration->getExecutable();
        if (!$this->testExecutable($executable)) {
            if ($executable) {
                $message = sprintf(
                    'Couldn\'t find executable file specified in ' .
                    'configuration (`%s`). Please ensure it exists and is ' .
                    'executable.',
                    $executable
                );
                $verbosityLevel = Verbosity::LEVEL_WARNING;
                $this->ioController->writeLine($message, $verbosityLevel);
            }
            return null;
        }
        $message = sprintf(
            'Found executable specified by configuration (`%s`)',
            $executable
        );
        $this->ioController->writeLine($message, Verbosity::LEVEL_INFO);
        return $executable;
    }

    /**
     * Retrieves generic allure executable if it's installed in system.
     *
     * @return null|string
     * @since 0.1.0
     */
    private function getGenericExecutable()
    {
        $executable = $this->fileLocator->locateExecutable('allure');
        // this check is quite paranoid (file locator should return executables
        // only), but i'll leave it in place.
        if (!$this->testExecutable($executable)) {
            return null;
        }
        $message = sprintf(
            'Found generic Allure executable (`%s`)',
            $executable
        );
        $this->ioController->writeLine($message, Verbosity::LEVEL_INFO);
        return $executable;
    }

    /**
     * Tests location result for being executable or not.
     *
     * @param string|null $executable Path to executable.
     *
     * @return bool
     * @since 0.1.0
     */
    private function testExecutable($executable)
    {
        if (!$executable) {
            return false;
        }
        if (!is_executable($executable)) {
            $message = sprintf(
                'File `%s` doesn\'t appear to be executable',
                $executable
            );
            $this->ioController->writeLine($message, Verbosity::LEVEL_NOTICE);
            return false;
        }
        $message = sprintf('Found valid executable file (`%s`)', $executable);
        $this->ioController->writeLine($message);
        return true;
    }
}
