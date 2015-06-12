<?php

namespace Etki\Testing\AllureFramework\Runner\Tests\Functional\Run\Scenario;

use Closure;
use Etki\Testing\AllureFramework\Runner\Configuration\Configuration;
use Etki\Testing\AllureFramework\Runner\IO\Controller\DummyController;
use Etki\Testing\AllureFramework\Runner\Run\Scenario\JarAssetUrlResolver;
use Etki\Testing\AllureFramework\Runner\Run\Scenario\JarDownloader;
use Etki\Testing\AllureFramework\Runner\Run\Scenario\JarLocator;
use Etki\Testing\AllureFramework\Runner\Run\Scenario\JarResolver;
use Etki\Testing\AllureFramework\Runner\Tests\Support\Reflection\Registry;
use Etki\Testing\AllureFramework\Runner\Tests\Support\Test\AbstractClassAwareTest;
use Etki\Testing\AllureFramework\Runner\Utility\Filesystem;
use Exception;
use FunctionalTester;
use PHPUnit_Framework_MockObject_MockObject as Mock;

/**
 * Tests `.jar` file resolver.
 *
 * @SuppressWarnings(PHPMD.TooManyMethods)
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Tests\Functional\Run\Scenario
 * @author  Etki <etki@etki.name>
 */
class JarResolverTest extends AbstractClassAwareTest
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
        return Registry::JAR_RESOLVER_CLASS;
    }

    /**
     * Creates test instance.
     *
     * todo this is functional testing, why so mockily?
     *
     * @param Configuration       $configuration
     * @param JarLocator          $jarLocator
     * @param JarDownloader       $jarDownloader
     * @param JarAssetUrlResolver $assetUrlResolver
     * @param Filesystem          $filesystemHelper
     *
     * @return JarResolver
     * @since 0.1.0
     */
    protected function createTestInstance(
        Configuration $configuration = null,
        JarLocator $jarLocator = null,
        JarDownloader $jarDownloader = null,
        JarAssetUrlResolver $assetUrlResolver = null,
        Filesystem $filesystemHelper = null
    ) {
        $instance = parent::createTestInstance(
            $configuration ?: new Configuration,
            $jarLocator ?: $this->createJarLocatorMock(),
            $jarDownloader ?: $this->createJarDownloaderMock(),
            $assetUrlResolver ?: $this->createAssetUrlResolverMock(),
            $filesystemHelper ?: $this->createFilesystemHelperMock(),
            new DummyController
        );
        return $instance;
    }

    /**
     * Creates filesystem helper mock.
     *
     * @param Closure $existenceChecker Function to run instead of `exists()`
     *                                  method.
     *
     * @return Mock|Filesystem
     * @since 0.1.0
     */
    private function createFilesystemHelperMock(
        Closure $existenceChecker = null
    ) {
        $mock = $this
            ->getMockFactory(Registry::FILESYSTEM_HELPER_CLASS)
            ->getConstructorlessMock();
        $invocationMocker = $mock
            ->expects($this->any())
            ->method('exists');
        if ($existenceChecker) {
            $invocationMocker->willReturnCallback($existenceChecker);
        } else {
            $invocationMocker->willReturn(null);
        }
        return $mock;
    }

    /**
     * Creates asset url resolver.
     *
     * @param string $url URL to return as resolved one.
     *
     * @return Mock|JarAssetUrlResolver
     * @since 0.1.0
     */
    private function createAssetUrlResolverMock($url = null)
    {
        $mock = $this
            ->getMockFactory(Registry::JAR_ASSET_URL_RESOLVER_CLASS)
            ->getConstructorlessMock();
        $mock
            ->expects($this->any())
            ->method('resolveUrl')
            ->willReturn($url);
        return $mock;
    }

    /**
     * Creates jar downloader mock.
     *
     * @param string $downloadedFileLocation Path to return as downloaded file
     *                                       location.
     *
     * @SuppressWarnings(PHPMD.LongVariableName)
     *
     * @return Mock|JarDownloader
     * @since 0.1.0
     */
    private function createJarDownloaderMock($downloadedFileLocation = null)
    {
        $mock = $this
            ->getMockFactory(Registry::JAR_DOWNLOADER_CLASS)
            ->getConstructorlessMock();
        $mock
            ->expects($this->any())
            ->method('downloadJar')
            ->withAnyParameters()
            ->willReturn($downloadedFileLocation);
        return $mock;
    }

    /**
     * Creates jar downloader mock which will burn in exception explosion on
     * `download()` call.
     *
     * @param Exception $exception Exception instance.
     *
     * @return Mock|JarDownloader
     * @since 0.1.0
     */
    private function createPreparedExceptionalJarDownloaderMock(
        Exception $exception
    ) {
        $mock = $this
            ->getMockFactory(Registry::JAR_DOWNLOADER_CLASS)
            ->getConstructorlessMock();
        $mock
            ->expects($this->any())
            ->method('downloadJar')
            ->withAnyParameters()
            ->willThrowException($exception);
        return $mock;
    }

    /**
     * Creates jar file locator mock.
     *
     * @param string $jarLocation Location to return on `getJar()` call.
     *
     * @return Mock|JarLocator
     * @since 0.1.0
     */
    private function createJarLocatorMock($jarLocation = null)
    {
        $mock = $this
            ->getMockFactory(Registry::JAR_LOCATOR_CLASS)
            ->getConstructorlessMock();
        $mock
            ->expects($this->any())
            ->method('getJar')
            ->willReturn($jarLocation);
        return $mock;
    }

    // tests

    /**
     * Tests that null will be returned if all dependent services fail.
     * Basically this test verifies that mocking has been done correctly.
     *
     * @return void
     * @since 0.1.0
     */
    public function testUnsuccessfulResolve()
    {
        $this->assertNull($this->createTestInstance()->resolveJar());
    }

    /**
     * Tests scenario in which configuration points to installed jar file.
     *
     * @return void
     * @since 0.1.0
     */
    public function testConfigurationSpecifiedJarScenario()
    {
        $location = '/tmp/jirchik/allure.jar';
        $configuration = new Configuration;
        $configuration->setJar($location);
        $checker = function () {
            return true;
        };
        $filesystemHelperMock = $this->createFilesystemHelperMock($checker);
        
        $instance = $this->createTestInstance(
            $configuration,
            null,
            null,
            null,
            $filesystemHelperMock
        );
        $this->assertSame($location, $instance->resolveJar());
    }

    /**
     * Tests behaviour if jar specified in configuration doesn't exist (and all
     * other resolve options return nothing as well).
     *
     * @return void
     * @since 0.1.0
     */
    public function testMissingConfigurationSpecifiedJarScenario()
    {
        $location = '/tmp/jirchik/allure.jar';
        $configuration = new Configuration;
        $configuration->setJar($location);
        $checker = function () {
            return false;
        };
        $filesystemHelperMock = $this->createFilesystemHelperMock($checker);

        $instance = $this->createTestInstance(
            $configuration,
            null,
            null,
            null,
            $filesystemHelperMock
        );
        $this->assertNull($instance->resolveJar());
    }

    /**
     * Tests scenario in which allure jar is found on disk.
     *
     * @return void
     * @since 0.1.0
     */
    public function testSuccessfulDiskSearchScenario()
    {
        $location = '/tmp/jirchik/allure.jar';
        $jarLocator = $this->createJarLocatorMock($location);
        $instance = $this->createTestInstance(new Configuration, $jarLocator);
        
        $this->assertSame($location, $instance->resolveJar());
    }

    /**
     * Tests scenario in which jar is downloaded from external site.
     *
     * @return void
     * @since 0.1.0
     */
    public function testDownloadingJarScenario()
    {
        $location = '/tmp/jirchik/allure.jar';
        $url = 'http://shameless-product-placement.io';
        $jarDownloader = $this->createJarDownloaderMock($location);
        $assetUrlResolver = $this->createAssetUrlResolverMock($url);
        $configuration = new Configuration;
        $configuration->setDownloadMissingJar(true);
        $instance = $this->createTestInstance(
            $configuration,
            null,
            $jarDownloader,
            $assetUrlResolver
        );
        
        $this->assertSame($location, $instance->resolveJar());
    }

    /**
     * Tests scenario in which file has to but can't be downloaded due to
     * exception.
     *
     * @return void
     * @since 0.1.0
     */
    public function testExceptionalDownloadingJarScenario()
    {
        $message = 'You\'re all fools and don\'t accept treatment';
        $exception = new Exception($message);
        $url = 'http://shameless-product-placement.io';
        $jarDownloader
            = $this->createPreparedExceptionalJarDownloaderMock($exception);
        $assetUrlResolver = $this->createAssetUrlResolverMock($url);
        $configuration = new Configuration;
        $configuration->setDownloadMissingJar(true);
        $instance = $this->createTestInstance(
            $configuration,
            null,
            $jarDownloader,
            $assetUrlResolver
        );

        $this->assertNull($instance->resolveJar());
    }

    /**
     * Tests scenario in which asset resolver couldn't resolve asset url.
     *
     * @return void
     * @since 0.1.0
     */
    public function testUnresolvedAssetScenario()
    {
        $assetUrlResolver = $this->createAssetUrlResolverMock(null);
        $configuration = new Configuration;
        $configuration->setDownloadMissingJar(true);
        $instance = $this->createTestInstance(
            $configuration,
            null,
            null,
            $assetUrlResolver
        );

        $this->assertNull($instance->resolveJar());
    }
}
