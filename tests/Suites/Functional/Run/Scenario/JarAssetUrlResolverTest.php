<?php

namespace Etki\Testing\AllureFramework\Runner\Tests\Functional\Run\Scenario;

use Closure;
use Etki\Testing\AllureFramework\Runner\Run\Scenario\JarAssetUrlResolver;
use Etki\Testing\AllureFramework\Runner\Api\Github\ReleaseResolver;
use Etki\Testing\AllureFramework\Runner\Api\Github\ReleaseAssetResolver;
use Etki\Testing\AllureFramework\Runner\Configuration\Configuration;
use Etki\Testing\AllureFramework\Runner\IO\Controller\DummyController;
use Etki\Testing\AllureFramework\Runner\Tests\Support\Reflection\Registry;
use Etki\Testing\AllureFramework\Runner\Tests\Support\Test\AbstractClassAwareTest;
use FunctionalTester;
use PHPUnit_Framework_MockObject_MockObject as Mock;

/**
 * Tests resolver of url to zip asset containing allure jar file.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Tests\Functional\Run\Scenario
 * @author  Etki <etki@etki.name>
 */
class JarAssetUrlResolverTest extends AbstractClassAwareTest
{
    /**
     * Tester instance.
     *
     * @type FunctionalTester
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
        return Registry::JAR_ASSET_URL_RESOLVER_CLASS;
    }

    /**
     * Creates test instance.
     *
     * @param ReleaseResolver      $releaseResolver Allure release resolver.
     * @param ReleaseAssetResolver $assetResolver   Release asset resolver.
     * @param Configuration        $configuration   Configuration.
     *
     * @return JarAssetUrlResolver
     * @since 0.1.0
     */
    protected function createTestInstance(
        ReleaseResolver $releaseResolver = null,
        ReleaseAssetResolver $assetResolver = null,
        Configuration $configuration = null
    ) {
        $instance = parent::createTestInstance(
            $configuration ?: new Configuration,
            $releaseResolver ?: $this->createReleaseResolverMock(),
            $assetResolver ?: $this->createAssetResolverMock(),
            new DummyController
        );
        return $instance;
    }

    /**
     * Creates release resolver mock.
     *
     * @param Closure $latestReleaseFetcher   Function that will imitate latest
     *                                        release fetching.
     * @param Closure $specificReleaseFetcher Function that will imitate
     *                                        specific release fetching.
     *
     * @SuppressWarnings(PHPMD.LongVariableName)
     *
     * @return Mock|ReleaseResolver
     * @since 0.1.0
     */
    private function createReleaseResolverMock(
        Closure $latestReleaseFetcher = null,
        Closure $specificReleaseFetcher = null
    ) {
        $mock = $this
            ->getMockFactory(Registry::RELEASE_RESOLVER_CLASS)
            ->getConstructorlessMock();
        $latestReleaseFetchMock = $mock
            ->expects($this->any())
            ->method('getLatestRelease');
        if ($latestReleaseFetcher) {
            $latestReleaseFetchMock->willReturnCallback($latestReleaseFetcher);
        } else {
            $latestReleaseFetchMock->willReturn(null);
        }
        $specificReleaseFetchMock = $mock
            ->expects($this->any())
            ->method('getSpecificRelease');
        if ($specificReleaseFetcher) {
            $specificReleaseFetchMock
                ->willReturnCallback($specificReleaseFetcher);
        } else {
            $specificReleaseFetchMock->willReturn(null);
        }
        return $mock;
    }

    /**
     * Creates release asset resolver mock.
     *
     * @param string $url URL to return on `getFirstZipAssetUrl()` call.
     *
     * @return Mock|ReleaseAssetResolver
     * @since 0.1.0
     */
    private function createAssetResolverMock($url = null)
    {
        $mock = $this
            ->getMockFactory(Registry::RELEASE_ASSET_RESOLVER_CLASS)
            ->getConstructorlessMock();
        $mock
            ->expects($this->any())
            ->method('getFirstZipAssetUrl')
            ->willReturn($url);
        return $mock;
    }

    // tests

    /**
     * Verifies correct work if no provider has returned a positive answer.
     *
     * @return void
     * @since 0.1.0
     */
    public function testFailureScenario()
    {
        $this->assertNull($this->createTestInstance()->resolveUrl());
    }

    /**
     * Tests that correctly-specified release in configuration works out well.
     *
     * @SuppressWarnings(PHPMD.LongVariableName)
     *
     * @return void
     * @since 0.1.0
     */
    public function testConfigurationSpecifiedReleaseScenario()
    {
        $url = 'http://www.homyak.com/eto-ya';
        $tag = '9.9';
        $specificReleaseFetcher = function () {
            return array(array(),);
        };
        $releaseResolverMock
            = $this->createReleaseResolverMock(null, $specificReleaseFetcher);
        $assetUrlResolverMock = $this->createAssetResolverMock($url);
        $configuration = new Configuration;
        $configuration->setPreferredAllureVersion($tag);
        $instance = $this->createTestInstance(
            $releaseResolverMock,
            $assetUrlResolverMock,
            $configuration
        );
        $this->assertSame($url, $instance->resolveUrl());
    }

    /**
     * Tests that automatically resolved release works out well.
     *
     * @return void
     * @since 0.1.0
     */
    public function testAutomaticResolvedReleaseScenario()
    {
        $url = 'http://www.homyak.com/eto-ya';
        $latestReleaseFetcher = function () {
            return array(array(),);
        };
        $releaseResolverMock
            = $this->createReleaseResolverMock($latestReleaseFetcher);
        $assetUrlResolverMock = $this->createAssetResolverMock($url);
        
        $instance = $this->createTestInstance(
            $releaseResolverMock,
            $assetUrlResolverMock
        );
        $this->assertSame($url, $instance->resolveUrl());
    }

    /**
     * Tests behaviour when resolver can't get latest release.
     *
     * @return void
     * @since 0.1.0
     */
    public function testErroneousLatestReleaseGettingScenario()
    {
        $releaseResolver = $this->createReleaseResolverMock(null);
        $instance = $this->createTestInstance($releaseResolver);
        $this->assertNull($instance->resolveUrl());
    }
}
