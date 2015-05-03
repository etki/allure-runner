<?php

namespace Etki\Testing\AllureFramework\Runner\Tests\Support\Mock\Factory\Utility;

use Codeception\TestCase\Test;
use Etki\Testing\AllureFramework\Runner\Tests\Support\Mock\Factory\AbstractMockFactory;
use Etki\Testing\AllureFramework\Runner\Utility\Filesystem;
use PHPUnit_Framework_MockObject_MockObject as Mock;

/**
 * This factory produces filesystem helper mocks.
 *
 * @method Filesystem|Mock getMock(Test $test)
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Tests\Support\Mock\Factory\Utility
 * @author  Etki <etki@etki.name>
 */
class FilesystemMockFactory extends AbstractMockFactory
{
    /**
     * Mocked class FQCN.
     *
     * @since 0.1.0
     */
    const MOCKED_CLASS
        = 'Etki\Testing\AllureFramework\Runner\Utility\Filesystem';

    /**
     * @return string
     * @since 0.1.0
     */
    public function getMockedClass()
    {
        return self::MOCKED_CLASS;
    }
}
