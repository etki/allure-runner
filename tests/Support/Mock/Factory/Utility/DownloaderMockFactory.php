<?php

namespace Etki\Testing\AllureFramework\Runner\Tests\Support\Mock\Factory\Utility;

use Etki\Testing\AllureFramework\Runner\Tests\Support\Mock\Factory\AbstractMockFactory;
use Etki\Testing\AllureFramework\Runner\Utility\Downloader;
use Codeception\TestCase\Test;
use PHPUnit_Framework_MockObject_MockObject as Mock;

/**
 * Crates fake downloaders.
 *
 * @method Mock|Downloader getMock(Test $test)
 * @method Mock|Downloader getDummyMock(Test $test)
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Tests\Support\Mock\Factory
 * @author  Etki <etki@etki.name>
 */
class DownloaderMockFactory extends AbstractMockFactory
{
    /**
     * Mocked class signature.
     *
     * @since 0.1.0
     */
    const MOCKED_CLASS
        = 'Etki\Testing\AllureFramework\Runner\Utility\Downloader';

    /**
     * @return string
     * @since 0.1.0
     */
    public function getMockedClass()
    {
        return self::MOCKED_CLASS;
    }
}
