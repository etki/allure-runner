<?php

namespace Etki\Testing\AllureFramework\Runner\Tests\Support\Mock\Factory\Vendor\Github;

use Etki\Testing\AllureFramework\Runner\Tests\Support\Mock\Factory\AbstractMockFactory;
use Etki\Testing\AllureFramework\Runner\Tests\Support\Mock\Factory\BasicMockFactory;
use Etki\Testing\AllureFramework\Runner\Tests\Support\Reflection\Registry;
use Github\Client as GithubApiClient;
use PHPUnit_Framework_MockObject_MockObject as Mock;

/**
 * Creates github api client mocks.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Tests\Support\Mock\Factory\Vendor\Github
 * @author  Etki <etki@etki.name>
 */
class ClientMockFactory extends AbstractMockFactory
{
    /**
     * Returns mocked class FQCN.
     *
     * @return string
     * @since 0.1.0
     */
    public function getMockedClass()
    {
        return Registry::GITHUB_API_CLIENT_CLASS;
    }

    /**
     * Creates prepared client mock.
     *
     * @param array $releases Releases to return.
     *
     * @SuppressWarnings(PHPMD.LongVariableName)
     *
     * @return Mock|GithubApiClient
     * @since 0.1.0
     */
    public function getPreparedMock(array $releases = null)
    {
        $releaseClientMockFactory
            = new BasicMockFactory(Registry::GITHUB_API_RELEASE_CLIENT_CLASS);
        $releaseClientMockFactory->setTest($this->getTest());
        $releaseClientMock
            = $releaseClientMockFactory->getConstructorlessMock();
        $releaseClientMock
            ->expects($this->getTest()->any())
            ->method('all')
            ->willReturn($releases);
        $repositoryClientMockFactory = new BasicMockFactory(
            Registry::GITHUB_API_REPOSITORY_CLIENT_CLASS
        );
        $repositoryClientMockFactory->setTest($this->getTest());
        $repositoryClientMock
            = $repositoryClientMockFactory->getConstructorlessMock();
        $repositoryClientMock
            ->expects($this->getTest()->any())
            ->method('releases')
            ->willReturn($releaseClientMock);
        $mock = $this->getMock();
        $methods = array('api', '__call',);
        foreach ($methods as $method) {
            $mock
                ->expects($this->getTest()->any())
                ->method($method)
                ->willReturn($repositoryClientMock);
        }
        return $mock;
    }
}
