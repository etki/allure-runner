<?php

namespace Etki\Testing\AllureFramework\Runner\Run\Scenario;

use Etki\Testing\AllureFramework\Runner\Configuration\Configuration;
use Etki\Testing\AllureFramework\Runner\Configuration\Verbosity;
use Etki\Testing\AllureFramework\Runner\IO\IOControllerInterface;
use Exception;

/**
 * Jar resolver.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Run\Scenario
 * @author  Etki <etki@etki.name>
 */
class JarResolver
{
    /**
     * Configuration instance.
     *
     * @type Configuration
     * @since 0.1.0
     */
    private $configuration;
    /**
     * I\O controller.
     *
     * @type IOControllerInterface
     * @since 0.1.0
     */
    private $ioController;
    /**
     * Downloader instance.
     *
     * @type JarDownloader
     * @since 0.1.0
     */
    private $jarDownloader;
    /**
     * `.jar` file locator.
     *
     * @type JarLocator
     * @since 0.1.0
     */
    private $jarLocator;
    /**
     * Source URL resolver.
     *
     * @type JarAssetUrlResolver
     * @since 0.1.0
     */
    private $assetUrlResolver;

    /**
     * Initializer.
     *
     * @param Configuration         $configuration    Runner configuration.
     * @param JarLocator            $jarLocator       `.jar` file locator.
     * @param JarDownloader         $jarDownloader    `.jar` file downloader.
     * @param JarAssetUrlResolver   $assetUrlResolver Source url resolver
     * @param IOControllerInterface $ioController     I\O controller.
     *
     * @return self
     * @since 0.1.0
     */
    public function __construct(
        Configuration $configuration,
        JarLocator $jarLocator,
        JarDownloader $jarDownloader,
        JarAssetUrlResolver $assetUrlResolver,
        IOControllerInterface $ioController
    ) {
        $this->configuration = $configuration;
        $this->jarLocator = $jarLocator;
        $this->jarDownloader = $jarDownloader;
        $this->assetUrlResolver = $assetUrlResolver;
        $this->ioController = $ioController;
    }

    /**
     * Resolves `.jar` file location.
     *
     * @return null|string
     * @since 0.1.0
     */
    public function resolveJar()
    {
        $message = 'Looking for `.jar` file';
        $this->ioController->writeLine($message, Verbosity::LEVEL_INFO);
        if ($jar = $this->getConfigurationJar()) {
            // @todo and file_exists
            return $jar;
        }
        if ($jar = $this->jarLocator->getJar()) {
            return $jar;
        }
        if ($this->configuration->shouldDownloadMissingJar()
            && $jar = $this->downloadJar()
        ) {
            return $jar;
        }
        $message = 'Couldn\'t find or download Allure CLI `.jar` file';
        $this->ioController->writeLine($message, Verbosity::LEVEL_WARNING);
        return null;
    }

    /**
     * Retrieves `.jar` file location specified in configuration.
     *
     * @return null|string
     * @since 0.1.0
     */
    private function getConfigurationJar()
    {
        $message = 'Looking for `.jar` file in runner configuration';
        $this->ioController->writeLine($message, Verbosity::LEVEL_NOTICE);
        if (!($jar = $this->configuration->getJar())) {
            $message = 'Configuration doesn\'t contain jar file location';
            $this->ioController->writeLine($message, Verbosity::LEVEL_NOTICE);
            return null;
        }
        if (!file_exists($jar)) {
            $message = sprintf(
                'Jar file `%s` (specified in configuration) doesn\'t exist',
                $jar
            );
            $this->ioController->writeLine($message, Verbosity::LEVEL_WARNING);
            return null;
        }
        $message = sprintf(
            'Found `.jar` file `%s` specified in configuration',
            $jar
        );
        $this->ioController->writeLine($message, Verbosity::LEVEL_NOTICE);
        return $jar;
    }

    /**
     * Downloads fresh copy of `.jar` file and returns path to it.
     *
     * @return null|string
     * @since 0.1.0
     */
    private function downloadJar()
    {
        $location = null;
        try {
            $url = $this->assetUrlResolver->resolveUrl();
            if (!$url) {
                $message
                    = 'Couldn\'t resolve link to `.jar` file download source';
                $this->ioController->writeLine(
                    $message,
                    Verbosity::LEVEL_WARNING
                );
                return null;
            }
            $location = $this->jarDownloader->downloadJar($url);
        } catch (Exception $e) {
            $message = sprintf(
                '`.jar` file download has failed with `%s` exception',
                get_class($e)
            );
            $this->ioController->writeLine($message, Verbosity::LEVEL_WARNING);
            $this->ioController->writeLine(
                $e->getMessage(),
                Verbosity::LEVEL_NOTICE
            );
            $this->ioController->writeLine(
                $e->getTraceAsString(),
                Verbosity::LEVEL_DEBUG
            );
        }
        return $location;
    }
}
