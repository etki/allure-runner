<?php

namespace Etki\Testing\AllureFramework\Runner\Run\Scenario;

use Etki\Testing\AllureFramework\Runner\Configuration\Verbosity;
use Etki\Testing\AllureFramework\Runner\Environment\Filesystem\FileLocator;
use Etki\Testing\AllureFramework\Runner\IO\IOControllerInterface;

/**
 * This class locates jar file on disk.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Run
 * @author  Etki <etki@etki.name>
 */
class JarLocator
{
    /**
     * File locator instance.
     *
     * @type FileLocator
     * @since 0.1.0
     */
    private $fileLocator;
    /**
     * I\O controller.
     *
     * @type IOControllerInterface
     * @since 0.1.0
     */
    private $ioController;

    /**
     * Initializer.
     *
     * @param FileLocator           $fileLocator  File locator instance.
     * @param IOControllerInterface $ioController I\O controller.
     *
     * @codeCoverageIgnore
     *
     * @return self
     * @since 0.1.0
     */
    public function __construct(
        FileLocator $fileLocator,
        IOControllerInterface $ioController
    ) {
        $this->fileLocator = $fileLocator;
        $this->ioController = $ioController;
    }

    /**
     * Returns path to jar file or null.
     *
     * @return string|null
     * @since 0.1.0
     */
    public function getJar()
    {
        $ioc = $this->ioController;
        $message = 'Locating `.jar` file';
        $ioc->writeLine($message, Verbosity::LEVEL_INFO);
        $variants = array('allure.jar', 'allure-cli.jar',);
        $jar = null;
        foreach ($variants as $variant) {
            $message = sprintf('Trying `%s`... ', $variant);
            $ioc->write($message, Verbosity::LEVEL_DEBUG);
            if ($jar = $this->fileLocator->locateFile($variant)) {
                $ioc->writeLine('Success.', Verbosity::LEVEL_DEBUG);
                $jar = array_shift($jar);
                break;
            } else {
                $ioc->writeLine('Unsuccessful.', Verbosity::LEVEL_DEBUG);
            }
        }
        $message = 'Couldn\'t find Allure `.jar` file';
        if ($jar) {
            $message = sprintf(
                'Successfully found Allure `.jar` at `%s`',
                $jar
            );
        }
        $level = $jar ? Verbosity::LEVEL_INFO : Verbosity::LEVEL_WARNING;
        $ioc->writeLine($message, $level);
        return $jar;
    }
}
