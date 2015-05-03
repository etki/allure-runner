<?php

namespace Etki\Testing\AllureFramework\Runner\Tests\Support\Mock\Factory;

use Etki\Testing\AllureFramework\Runner\IO\IOControllerInterface;
use Codeception\TestCase;
use PHPUnit_Framework_MockObject_MockBuilder as MockBuilder;
use PHPUnit_Framework_MockObject_MockObject as Mock;

/**
 * Factory for I\O controller.
 *
 * todo inherit from AbstractMockFactory.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Tests\Support\Mock\Factory
 * @author  Etki <etki@etki.name>
 */
class IOControllerMockFactory
{
    /**
     * Mocked interface FQIN.
     *
     * @since 0.1.0
     */
    const MOCKED_CLASS
        = '\Etki\Testing\AllureFramework\Runner\IO\IOControllerInterface';

    /**
     * Returns mock.
     *
     * @param TestCase $testCase
     *
     * @return Mock|IOControllerInterface
     * @since 0.1.0
     */
    public function getMock(TestCase $testCase)
    {
        $mockBuilder = new MockBuilder($testCase, self::MOCKED_CLASS);
        $methods = array('write', 'writeLine', 'writeLines', 'setVerbosity',);
        $mockBuilder->setMethods($methods);
        return $mockBuilder->getMock();
    }
}
