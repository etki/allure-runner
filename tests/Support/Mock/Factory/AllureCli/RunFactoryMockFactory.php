<?php

namespace Etki\Testing\AllureFramework\Runner\Tests\Support\Mock\Factory\AllureCli;

use Codeception\TestCase\Test;
use Etki\Testing\AllureFramework\Runner\AllureCli\RunFactory;
use Etki\Testing\AllureFramework\Runner\Tests\Support\Mock\Factory\AbstractMockFactory;

/**
 * Creates RunFactory mocks.
 *
 * @method RunFactory getMock(Test $test)
 * @method RunFactory getDummyMock(Test $test)
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Tests\Support\Mock\Factory\AllureCli
 * @author  Etki <etki@etki.name>
 */
class RunFactoryMockFactory extends AbstractMockFactory
{
    /**
     * Mocked class name.
     *
     * @since 0.1.0
     */
    const MOCKED_CLASS
        = 'Etki\Testing\AllureFramework\Runner\AllureCli\RunFactory';

    /**
     * Returns mocked class name.
     *
     * @return string
     * @since 0.1.0
     */
    public function getMockedClass()
    {
        return self::MOCKED_CLASS;
    }
}
