<?php

namespace Etki\Testing\AllureFramework\Runner\Run\Scenario;

use Etki\Testing\AllureFramework\Runner\Configuration\Verbosity;
use Etki\Testing\AllureFramework\Runner\IO\IOControllerInterface;
use Etki\Testing\AllureFramework\Runner\Utility\Downloader;
use Etki\Testing\AllureFramework\Runner\Utility\Extractor;
use Etki\Testing\AllureFramework\Runner\Utility\Filesystem\TemporaryNodesManager;
use Etki\Testing\AllureFramework\Runner\Utility\PhpApi;

/**
 * This class is responsible for jar file download.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Run\Scenario
 * @author  Etki <etki@etki.name>
 */
class JarDownloader
{
    /**
     * Downloader instance.
     *
     * @type Downloader
     * @since 0.1.0
     */
    private $downloader;
    /**
     * Extractor instance.
     *
     * @type Extractor
     * @since 0.1.0
     */
    private $extractor;
    /**
     * I\O controller instance.
     *
     * @type IOControllerInterface
     * @since 0.1.0
     */
    private $ioController;
    /**
     * Temporary filesystem nodes manager.
     *
     * @type TemporaryNodesManager
     * @since 0.1.0
     */
    private $temporaryNodesManager;

    /**
     * Initializer.
     *
     * @param Downloader            $downloader            Downloader instance.
     * @param Extractor             $extractor             Extractor instance.
     * @param IOControllerInterface $ioController          I\O controller.
     * @param TemporaryNodesManager $temporaryNodesManager Temporary filesystem
     *                                                     nodes manager.
     *
     * @SuppressWarnings(PHPMD.LongVariableName)
     *
     * @return self
     * @since 0.1.0
     */
    public function __construct(
        Downloader $downloader,
        Extractor $extractor,
        TemporaryNodesManager $temporaryNodesManager,
        IOControllerInterface $ioController
    ) {
        $this->downloader = $downloader;
        $this->extractor = $extractor;
        $this->ioController = $ioController;
        $this->temporaryNodesManager = $temporaryNodesManager;
    }

    /**
     * Downloads jar file.
     *
     * @param string $url Source URL.
     *
     * @SuppressWarnings(PHPMD.LongVariableName)
     *
     * @return string Downloaded file location.
     * @since 0.1.0
     */
    public function downloadJar($url)
    {
        $temporaryFile = $this
            ->temporaryNodesManager
            ->createTemporaryFile('allure-cli-zip-');
        $message = sprintf(
            'Downloading Allure CLI zip archive from `%s` to `%s`... ',
            $url,
            $temporaryFile
        );
        $this->ioController->write($message, Verbosity::LEVEL_DEBUG);
        $this->downloader->download($url, $temporaryFile);
        $this->ioController->writeLine('Done.', Verbosity::LEVEL_DEBUG);
        $target = $this
            ->temporaryNodesManager
            ->createTemporaryFile('allure-cli-jar');
        $file = 'lib/allure-cli.jar';
        $message = sprintf('Extracting jar file to `%s`... ', $target);
        $this->ioController->write($message);
        $this->extractor->extractFile($temporaryFile, $file, $target);
        $this->ioController->writeLine('Done.');
        return $target;
    }
}
