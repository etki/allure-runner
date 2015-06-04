<?php

namespace Etki\Testing\AllureFramework\Runner\Tests\Unit\Api\Github;

use Etki\Testing\AllureFramework\Runner\Api\Github\ReleaseAssetResolver;
use Etki\Testing\AllureFramework\Runner\Tests\Support\Data\Loader\ApiResponseLoader;
use Etki\Testing\AllureFramework\Runner\Tests\Support\Test\AbstractClassAwareTest;
use UnitTester;

/**
 * Tests release asset resolver.
 *
 * @method ReleaseAssetResolver createTestInstance()
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Tests\Unit\Api\Github
 * @author  Etki <etki@etki.name>
 */
class ReleaseAssetResolverTest extends AbstractClassAwareTest
{
    /**
     * Test subject instance.
     *
     * @since 0.1.0
     */
    const TESTED_CLASS
        = 'Etki\Testing\AllureFramework\Runner\Api\Github\ReleaseAssetResolver';
    /**
     * Tester instance.
     *
     * @type UnitTester
     * @since 0.1.0
     */
    protected $tester;

    // utility methods

    /**
     * Returns test subject FQCN.
     *
     * @return string
     * @since 0.1.0
     */
    public function getTestedClass()
    {
        return self::TESTED_CLASS;
    }
    
    // data providers

    /**
     * Returns [release, asset url] samples.
     *
     * @return array
     * @since 0.1.0
     */
    public function releaseUrlProvider()
    {
        // todo test infrastructure
        $loader = new ApiResponseLoader;
        $response = $loader->getResponse('github', 'releases.all');
        $latestReleaseId = $response['metadata']['meta']['latest-release-id'];
        $latestReleaseUrl = $response['metadata']['meta']['latest-release-url'];
        $data = array();
        foreach ($response['response'] as $release) {
            if ($release['id'] === $latestReleaseId) {
                $data[] = array($release, $latestReleaseUrl,);
                break;
            }
        }
        return $data;
    }

    /**
     * Provides samples that doesn't contain any valid releases at all
     *
     * @return array
     * @since 0.1.0
     */
    public function noValidAssetsReleaseProvider()
    {
        // todo test samples infrastructure
        // todo create another Github API response sample that doesn't contain any valid assets
        $loader = new ApiResponseLoader;
        $response = $loader->getResponse('github', 'releases.all');
        $releases = $response['response'];
        $data = array();
        foreach ($releases as $release) {
            if (empty($release['assets'])) {
                continue;
            }
            foreach ($release['assets'] as &$asset) {
                $asset['content_type'] = 'text/html';
            }
            $data[] = array($release,);
        }
        return $data;
    }
    
    // tests

    /**
     * Tests asset resolving.
     *
     * @param array  $release
     * @param string $expectedUrl
     *
     * @dataProvider releaseUrlProvider
     *
     * @return void
     * @since 0.1.0
     */
    public function testAssetResolve(array $release, $expectedUrl)
    {
        $instance = $this->createTestInstance();
        $url = $instance->getFirstZipAssetUrl($release);
        $this->assertSame($expectedUrl, $url);
    }

    /**
     * Verifies that null will be returned whenever there is nothing to return.
     *
     * @dataProvider noValidAssetsReleaseProvider
     *
     * @return void
     * @since 0.1.0
     */
    public function testFailingAssetResolve(array $release)
    {
        if (!empty($release['assets'])) {
            foreach ($release['assets'] as &$asset) {
                $asset['content_type'] = 'text/html';
            }
        }
        $instance = $this->createTestInstance();
        $this->assertNull($instance->getFirstZipAssetUrl($release));
    }
}
