<?php

namespace Etki\Testing\AllureFramework\Runner\Tests\Support\Mock\Factory;

use Etki\Testing\AllureFramework\Runner\Environment\ProcessFactory;
use Symfony\Component\Process\Process;
use Codeception\TestCase;
use PHPUnit_Framework_MockObject_MockBuilder as MockBuilder;
use PHPUnit_Framework_MockObject_MockObject as Mock;

/**
 * This class generates ProcessFactory mocks.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Tests\Support\Mock\Factory
 * @author  Etki <etki@etki.name>
 */
class ProcessFactoryMockFactory
{
    /**
     * Mocked class FQCN.
     *
     * @since 0.1.0
     */
    const MOCKED_CLASS
        = '\Etki\Testing\AllureFramework\Runner\Environment\ProcessFactory';

    /**
     * Returns mock object ready for setting up.
     *
     * @param TestCase $testCase Test case mock is created in.
     * @param Process  $process  Process instance to return on `getProcess()`
     *                           call.
     *
     * @return Mock|ProcessFactory
     * @since 0.1.0
     */
    public function getMock(TestCase $testCase, Process $process = null)
    {
        $mockBuilder = new MockBuilder($testCase, self::MOCKED_CLASS);
        $mockBuilder->setMethods(array('getProcess'));
        $mockBuilder->disableOriginalConstructor();
        $mock = $mockBuilder->getMock();
        if ($process) {
            $mock
                ->expects($testCase->any())
                ->method('getProcess')
                ->willReturnCallback(
                    function ($command) use ($process) {
                        $process->setCommandLine($command);
                        return $process;
                    }
                );
        }
        return $mock;
    }
}
