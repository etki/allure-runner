<?php

namespace Etki\Testing\AllureFramework\Runner\Tests\Unit\Api\Github;

use Etki\Testing\AllureFramework\Runner\Api\Github\ReleaseResolver;
use Etki\Testing\AllureFramework\Runner\Api\Github\ReleaseCollectionManipulator
    as CollectionManipulator;
use Github\Client as GithubApi;
use Github\Api\Repo as GithubRepositoryApi;
use Github\Api\Repository\Releases as GithubReleaseApi;
use Etki\Testing\AllureFramework\Runner\Tests\Support\Data\Loader\ApiResponseLoader;
use Etki\Testing\AllureFramework\Runner\Tests\Support\Test\AbstractClassAwareTest;
use PHPUnit_Framework_MockObject_MockObject as Mock;
use UnitTester;

/**
 * Tests release resolver.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Tests\Unit\Api\Github
 * @author  Etki <etki@etki.name>
 */
class ReleaseResolverTest extends AbstractClassAwareTest
{
    /**
     * Test subject FQCN.
     *
     * @since 0.1.0
     */
    const TESTED_CLASS
        = 'Etki\Testing\AllureFramework\Runner\Api\Github\ReleaseResolver';
    /**
     * Github API FQCN.
     *
     * @since 0.1.0
     */
    const GITHUB_API_CLASS = 'Github\Client';
    /**
     * Github release API FQCN.
     *
     * @since 0.1.0
     */
    const GITHUB_RELEASE_API_CLASS = 'Github\Api\Repository\Releases';
    /**
     * Github repository API FQCN.
     *
     * @since 0.1.0
     */
    const GITHUB_REPOSITORY_API_CLASS = 'Github\Api\Repo';
    /**
     * Release collection manipulator FQCN.
     *
     * @since 0.1.0
     */
    const COLLECTION_MANIPULATOR_CLASS
        = 'Etki\Testing\AllureFramework\Runner\Api\Github\ReleaseCollectionManipulator';
    
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

    /**
     * Creates test instance.
     *
     * @param GithubApi             $api                   Github API.
     * @param CollectionManipulator $collectionManipulator Release collection
     *                                                     filter/sorter.
     *
     * @SuppressWarnings(PHPMD.LongVariableName)
     *
     * @return ReleaseResolver
     * @since 0.1.0
     */
    protected function createTestInstance(
        GithubApi $api = null,
        CollectionManipulator $collectionManipulator = null
    ) {
        $api = $api ?: $this->createApiMock();
        if (!$collectionManipulator) {
            $collectionManipulator = $this->createCollectionManipulatorMock();
            $collectionManipulator
                ->expects($this->any())
                ->method('sortByDate')
                ->willReturnArgument(0);
            $collectionManipulator
                ->expects($this->any())
                ->method('filterPrereleases')
                ->willReturnArgument(0);
        }
        return parent::createTestInstance($api, $collectionManipulator);
    }

    /**
     * Creates github API mock.
     *
     * @return GithubApi|Mock
     * @since 0.1.0
     */
    private function createApiMock()
    {
        $mock = $this
            ->getMockFactory(self::GITHUB_API_CLASS)
            ->getConstructorlessMock();
        return $mock;
    }

    /**
     * Creates collection manipulator mock.
     *
     * @return CollectionManipulator|Mock
     * @since 0.1.0
     */
    private function createCollectionManipulatorMock()
    {
        $mock = $this
            ->getMockFactory(self::COLLECTION_MANIPULATOR_CLASS)
            ->getMock();
        return $mock;
    }

    /**
     * Creates prepared API mock.
     *
     * @param array $releases Releases to return on `$api->...->all()` call.
     *
     * @return GithubApi|Mock
     * @since 0.1.0
     */
    private function createPreparedApiMock(array $releases)
    {
        // todo move to mock factory
        $apiMock = $this->createApiMock();
        /** @type GithubReleaseApi|Mock $releaseApiMock */
        $releaseApiMock = $this
            ->getMockFactory(self::GITHUB_RELEASE_API_CLASS)
            ->getMock($apiMock);
        $releaseApiMock
            ->expects($this->atLeastOnce())
            ->method('all')
            ->willReturn($releases);
        /** @type GithubRepositoryApi|Mock $repoApiMock */
        $repoApiMock = $this
            ->getMockFactory(self::GITHUB_REPOSITORY_API_CLASS)
            ->getMock($apiMock);
        $repoApiMock
            ->expects($this->atLeastOnce())
            ->method('releases')
            ->willReturn($releaseApiMock);
        $apiMock
            ->expects($this->any())
            ->method('__call')
            ->willReturn($repoApiMock);
        return $apiMock;
    }

    /**
     * Returns already filtered and sanitized release collection.
     *
     * @return array
     * @since 0.1.0
     */
    private function getSanitizedReleaseCollection()
    {
        $loader = new ApiResponseLoader;
        // todo proper testing infrastructure
        $data = $loader->getResponse('github', 'releases.all');
        $releases = array();
        foreach ($data['response'] as $release) {
            if (!$release['prerelease']) {
                $releases[] = $release;
            }
        }
        usort(
            $releases,
            function ($a, $b) {
                return strtotime($b['published_at'])
                    - strtotime($a['published_at']);
            }
        );
        return $releases;
    }
    
    // data providers

    /**
     * Provides responses.
     *
     * @return array
     * @since 0.1.0
     */
    public function latestReleaseDataProvider()
    {
        $loader = new ApiResponseLoader;
        // todo proper testing infrastructure
        $data = $loader->getResponse('github', 'releases.all');
        return array(
            array(
                $data['response'],
                $data['metadata']['meta']['latest-release-id'],
            ),
        );
    }

    /**
     * Data provider for tag release search.
     *
     * @return array
     * @since 0.1.0
     */
    public function tagReleaseDataProvider()
    {
        $releases = $this->getSanitizedReleaseCollection();
        // todo proper testing infrastructure
        $loader = new ApiResponseLoader;
        $response = $loader->getResponse('github', 'releases.all');
        $tagMap = $response['metadata']['meta']['tag-map'];
        $data = array();
        foreach ($tagMap as $tag => $id) {
            $data[] = array($releases, $tag, $id);
        }
        return $data;
    }

    // tests

    /**
     * Tests release resolver.
     *
     * @param $response
     * @param $expectedReleaseId
     *
     * @dataProvider latestReleaseDataProvider
     *
     * @return void
     * @since 0.1.0
     */
    public function testLatestReleaseResolve($response, $expectedReleaseId)
    {
        $api = $this->createPreparedApiMock($response);
        $release = $this->createTestInstance($api)->getLatestRelease('', '');
        $this->assertArrayHasKey('id', $release);
        $this->assertSame($expectedReleaseId, $release['id']);
    }

    /**
     * Verifies that resolver returns null on empty responses.
     *
     * @return void
     * @since 0.1.0
     */
    public function testEmptyResponse()
    {
        $api = $this->createPreparedApiMock(array());
        $instance = $this->createTestInstance($api);
        $this->assertNull($instance->getLatestRelease('', ''));
        $this->assertNull($instance->getSpecificRelease('', '', ''));
    }

    /**
     * Verifies that correct release is found by tag.
     *
     * @param array  $releases   List of releases.
     * @param string $tag        Tag to search.
     * @param int    $expectedId Expected release id.
     *
     * @dataProvider tagReleaseDataProvider
     *
     * @return void
     * @since 0.1.0
     */
    public function testSpecificReleaseResolve(
        array $releases,
        $tag,
        $expectedId
    ) {
        $apiMock = $this->createPreparedApiMock($releases);
        $instance = $this->createTestInstance($apiMock);
        $release = $instance->getSpecificRelease('', '', $tag);
        $this->assertInternalType('array', $release);
        $this->assertArrayHasKey('id', $release);
        $this->assertSame($expectedId, $release['id']);
    }

    /**
     * Verifies that null is returned for nonexistent release tag.
     *
     * @return void
     * @since 0.1.0
     */
    public function testNonexistentReleaseResolve()
    {
        // todo proper test infrastructure
        $loader = new ApiResponseLoader;
        $response = $loader->getResponse('github', 'releases.all');
        $apiMock = $this->createPreparedApiMock($response['response']);
        $instance = $this->createTestInstance($apiMock);
        $this->assertNull($instance->getSpecificRelease('', '', 'nonexistent'));
    }
}
