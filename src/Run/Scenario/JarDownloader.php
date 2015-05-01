<?php

namespace Etki\Testing\AllureFramework\Runner\Run\Scenario;

use Etki\Testing\AllureFramework\Runner\Configuration\Verbosity;
use Etki\Testing\AllureFramework\Runner\IO\IOControllerInterface;
use Etki\Testing\AllureFramework\Runner\Utility\Downloader;
use Etki\Testing\AllureFramework\Runner\Utility\Extractor;
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
     * PHP API class instance.
     *
     * @type PhpApi
     * @since 0.1.0
     */
    private $phpApi;

    /**
     * Initializer.
     *
     * @param Downloader            $downloader   Downloader instance.
     * @param Extractor             $extractor    Extractor instance.
     * @param IOControllerInterface $ioController I\O controller.
     * @param PhpApi                $phpApi       PHP API instance.
     *
     * @return self
     * @since 0.1.0
     */
    public function __construct(
        Downloader $downloader,
        Extractor $extractor,
        IOControllerInterface $ioController,
        PhpApi $phpApi
    ) {
        $this->downloader = $downloader;
        $this->extractor = $extractor;
        $this->ioController = $ioController;
        $this->phpApi = $phpApi;
    }

    /**
     * Downloads jar file.
     *
     * @param string $url Source URL.
     *
     * @SuppressWarnings(PHPMD.LongVariableName)
     *
     * @return string
     * @since 0.1.0
     */
    public function downloadJar($url)
    {
        $temporaryFilesDirectory
            = $this->phpApi->getSystemTemporaryFilesDirectory();
        $temporaryFile = tempnam($temporaryFilesDirectory, 'allure-cli-');
        $message = sprintf('Downloading jar file to `%s`... ', $temporaryFile);
        $this->ioController->write($message, Verbosity::LEVEL_DEBUG);
        $this->downloader->download($url, $temporaryFile);
        $this->ioController->writeLine('Done.', Verbosity::LEVEL_DEBUG);
        $target = $temporaryFilesDirectory . '/allure-cli.jar';
        $file = 'lib/allure-cli.jar';
        $message = sprintf('Extracting jar file to `%s`... ', $target);
        $this->ioController->write($message);
        $this->extractor->extractFile($temporaryFile, $file, $target);
        $this->ioController->writeLine('Done.');
        return $target;
    }
}
