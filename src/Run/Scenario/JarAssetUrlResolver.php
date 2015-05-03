<?php

namespace Etki\Testing\AllureFramework\Runner\Run\Scenario;

use Etki\Testing\AllureFramework\Runner\Api\Github\LatestReleaseResolver;
use Etki\Testing\AllureFramework\Runner\Api\Github\ReleaseAssetResolver;
use Etki\Testing\AllureFramework\Runner\Configuration\Configuration;
use Etki\Testing\AllureFramework\Runner\Configuration\Verbosity;
use Etki\Testing\AllureFramework\Runner\Exception\Api\Github\ReleaseNotFoundException;
use Etki\Testing\AllureFramework\Runner\IO\IOControllerInterface;

/**
 * This class resolves end URL for asset downloading.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Run\Scenario
 * @author  Etki <etki@etki.name>
 */
class JarAssetUrlResolver
{
    /**
     * Allure runner configuration.
     *
     * @type Configuration
     * @since 0.1.0
     */
    private $configuration;
    /**
     * Github release asset resolver.
     *
     * @type ReleaseAssetResolver
     * @since 0.1.0
     */
    private $assetResolver;
    /**
     * Github release resolver.
     *
     * @type LatestReleaseResolver
     * @since 0.1.0
     */
    private $releaseResolver;
    /**
     * I/O controller.
     *
     * @type IOControllerInterface
     * @since 0.1.0
     */
    private $ioController;

    /**
     * Initializer.
     *
     * @param Configuration         $configuration   Allure runner
     *                                               configuration.
     * @param ReleaseAssetResolver  $assetResolver   Github asset resolver.
     * @param LatestReleaseResolver $releaseResolver Github release resolver.
     * @param IOControllerInterface $ioController    I/O controller.
     *
     * @return self
     * @since 0.1.0
     */
    public function __construct(
        Configuration $configuration,
        ReleaseAssetResolver $assetResolver,
        LatestReleaseResolver $releaseResolver,
        IOControllerInterface $ioController
    ) {
        $this->configuration = $configuration;
        $this->assetResolver = $assetResolver;
        $this->releaseResolver = $releaseResolver;
        $this->ioController = $ioController;
    }

    /**
     * Returns URL for the required asset or null if that asset can't be found.
     *
     * @return string|null
     * @since 0.1.0
     */
    public function resolveUrl()
    {
        $release = $this->configuration->getPreferredAllureVersion();
        if (!$release) {
            $release = $this->releaseResolver->getLatestRelease(
                Configuration::GITHUB_REPOSITORY_OWNER,
                Configuration::GITHUB_REPOSITORY_NAME
            );
        }
        if (!$release) {
            $message = 'Couldn\'t find corresponding release to fetch Allure ' .
                '`.jar` file from';
            $this->ioController->writeLine($message, Verbosity::LEVEL_WARNING);
            return null;
        }
        try {
            $assets = $this->assetResolver->getAssets(
                Configuration::GITHUB_REPOSITORY_OWNER,
                Configuration::GITHUB_REPOSITORY_NAME,
                $release
            );
        } catch (ReleaseNotFoundException $e) {
            $message = sprintf(
                'Github reported that release `%s` doesn\'t exist',
                $release
            );
            $this->ioController->writeLine($message, Verbosity::LEVEL_WARNING);
            return null;
        }
        if (!$assets) {
            $message = sprintf(
                'Github release `%s` doesn\'t contain any assets',
                $release
            );
            $this->ioController->writeLine($message, Verbosity::LEVEL_WARNING);
            return null;
        }
        $firstAsset = reset($assets);
        return $firstAsset['browser_download_url'];
    }
}
