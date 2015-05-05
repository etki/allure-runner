<?php

namespace Etki\Testing\AllureFramework\Runner\Tests\Support\Mock\Factory\Environment;

use Etki\Testing\AllureFramework\Runner\Environment\ProcessFactory;
use Etki\Testing\AllureFramework\Runner\Tests\Support\Mock\Factory\AbstractMockFactory;
use Symfony\Component\Process\Process;
use Codeception\TestCase;
use PHPUnit_Framework_MockObject_MockObject as Mock;

/**
 * This class generates ProcessFactory mocks.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Tests\Support\Mock\Factory
 * @author  Etki <etki@etki.name>
 */
class ProcessFactoryMockFactory extends AbstractMockFactory
{
    /**
     * Mocked class FQCN.
     *
     * @since 0.1.0
     */
    const MOCKED_CLASS
        = '\Etki\Testing\AllureFramework\Runner\Environment\ProcessFactory';

    /**
     * Returns mocked class FQCN.
     *
     * @return string
     * @since 0.1.0
     */
    public function getMockedClass()
    {
        return self::MOCKED_CLASS;
    }

    /**
     * Returns mock object ready for setting up.
     *
     * @param Process  $process  Process instance to return on `getProcess()`
     *                           call.
     *
     * @return Mock|ProcessFactory
     * @since 0.1.0
     */
    public function getPreparedMock(Process $process = null)
    {
        $mockBuilder = $this->getPreparedMockBuilder();
        $mockBuilder->disableOriginalConstructor();
        $mock = $mockBuilder->getMock();
        if ($process) {
            $mock
                ->expects($this->getTest()->any())
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
