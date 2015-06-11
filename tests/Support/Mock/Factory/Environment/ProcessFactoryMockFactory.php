<?php

namespace Etki\Testing\AllureFramework\Runner\Tests\Support\Mock\Factory\Environment;

use Etki\Testing\AllureFramework\Runner\Environment\ProcessFactory;
use Etki\Testing\AllureFramework\Runner\Tests\Support\Mock\Factory\AbstractMockFactory;
use Etki\Testing\AllureFramework\Runner\Tests\Support\Mock\Factory\Vendor\Symfony\Component\Process\ProcessMockFactory;
use mageekguy\atoum\tests\units\report;
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
     * Process FQCN.
     *
     * @since 0.1.0
     */
    const PROCESS_CLASS = 'Symfony\Component\Process\Process';

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
     * @param string $output   Process output.
     * @param int    $exitCode Process exit code.
     *
     * @return Mock|ProcessFactory
     * @since 0.1.0
     */
    public function getPreparedMock($output, $exitCode = 0)
    {
        /** @type ProcessMockFactory $processMockFactory */
        $processMockFactory
            = $this->getTest()->getMockFactory(self::PROCESS_CLASS);
        $processMock = $processMockFactory->getPreparedMock($exitCode, $output);
        $mockBuilder = $this->getPreparedMockBuilder();
        $mockBuilder->disableOriginalConstructor();
        $mock = $mockBuilder->getMock();
        $mock
            ->expects($this->getTest()->any())
            ->method('getProcess')
            ->willReturnCallback(
                function ($command) use ($processMock) {
                    $processMock->setCommandLine($command);
                    return $processMock;
                }
            );
        return $mock;
    }

    /**
     * Returns mock with injected process instance.
     *
     * @param Process $process Process to inject.
     *
     * @return Mock|ProcessFactory
     * @since 0.1.0
     */
    public function getInjectedMock(Process $process)
    {
        $mock = $this->getConstructorlessMock();
        $mock
            ->expects($this->getTest()->any())
            ->method('getProcess')
            ->willReturn($process);
        return $mock;
    }
}
