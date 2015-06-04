<?php

namespace Etki\Testing\AllureFramework\Runner\Run\Scenario;

use Etki\Testing\AllureFramework\Runner\Api\Github\ReleaseResolver;
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
     * @type ReleaseResolver
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
     * @param ReleaseResolver       $releaseResolver Github release resolver.
     * @param ReleaseAssetResolver  $assetResolver   Github release asset
     *                                               resolver.
     * @param IOControllerInterface $ioController    I/O controller.
     *
     * @return self
     * @since 0.1.0
     */
    public function __construct(
        Configuration $configuration,
        ReleaseResolver $releaseResolver,
        ReleaseAssetResolver $assetResolver,
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
        $url = null;
        if ($tag = $this->configuration->getPreferredAllureVersion()) {
            $url = $this->getUrlByReleaseTag($tag);
        }
        if (!$url) {
            $url = $this->getUrlFromLatestRelease();
        }
        if (!$url) {
            $message = 'Failed to resolve Allure download url';
            $this->ioController->writeLine($message, Verbosity::LEVEL_WARNING);
        }
        return $url;
    }

    /**
     * Fetches url by tag name.
     *
     * @param string $tag Tag to fetch asset for.
     *
     * @return string|null Asset url or null in case anything goes wrong.
     * @since 0.1.0
     */
    private function getUrlByReleaseTag($tag)
    {
        $message = sprintf('Getting asset url for release tagged `%s`', $tag);
        $this->ioController->writeLine($message, Verbosity::LEVEL_DEBUG);
        $release = $this->releaseResolver->getSpecificRelease(
            Configuration::GITHUB_REPOSITORY_OWNER,
            Configuration::GITHUB_REPOSITORY_NAME,
            $tag
        );
        if (!$release) {
            $message = sprintf('Failed to fetch release `%s`', $tag);
            $this->ioController->writeLine($message, Verbosity::LEVEL_WARNING);
            return null;
        }
        $url = $this->assetResolver->getFirstZipAssetUrl($release);
        if ($url) {
            $message = sprintf(
                'Successfully resolved asset url for release `%s` (%s)',
                $tag,
                $url
            );
            $this->ioController->writeLine($message, Verbosity::LEVEL_INFO);
        }
        return $url;
    }

    /**
     * Retrieves download url from latest available release.
     *
     * @return string|null Download url or null in case anything went wrong.
     * @since 0.1.0
     */
    private function getUrlFromLatestRelease()
    {
        $message = sprintf('Getting asset url for latest release');
        $this->ioController->writeLine($message, Verbosity::LEVEL_DEBUG);
        $release = $this->releaseResolver->getLatestRelease(
            Configuration::GITHUB_REPOSITORY_OWNER,
            Configuration::GITHUB_REPOSITORY_NAME
        );
        if (!$release) {
            $message = sprintf('Failed to fetch latest release');
            $this->ioController->writeLine($message, Verbosity::LEVEL_WARNING);
            return null;
        }
        $url = $this->assetResolver->getFirstZipAssetUrl($release);
        if ($url) {
            $message = sprintf(
                'Successfully resolved asset url for latest release (%s)',
                $url
            );
            $this->ioController->writeLine($message, Verbosity::LEVEL_INFO);
        }
        return $url;
    }
}
