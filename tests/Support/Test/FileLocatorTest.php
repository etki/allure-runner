<?php

namespace Etki\Testing\AllureFramework\Runner\Tests\Support\Test;

use Etki\Testing\AllureFramework\Runner\Environment\Filesystem\FileLocatorInterface;
use Etki\Testing\AllureFramework\Runner\Environment\ProcessFactory;
use Etki\Testing\AllureFramework\Runner\Tests\Support\Mock\Factory\ProcessFactoryMockFactory;
use Etki\Testing\AllureFramework\Runner\Tests\Support\Mock\Factory\SymfonyProcessMockFactory;
use Symfony\Component\Process\Process;
use PHPUnit_Framework_MockObject_MockObject as Mock;
use Codeception\TestCase\Test;

/**
 * Generic file locator test.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Tests\Support\Test
 * @author  Etki <etki@etki.name>
 */
abstract class FileLocatorTest extends Test
{
    /**
     * Retrieves tested class FQCN.
     *
     * @return string
     * @since 0.1.0
     */
    abstract public function getTestedClassName();

    /**
     * Creates mock for process class.
     *
     * @param int    $exitCode Process exit code.
     * @param string $output   Process output.
     *
     * @return Process|Mock
     * @since 0.1.0
     */
    protected function createProcessMock($exitCode = null, $output = null)
    {
        $processMockFactory = new SymfonyProcessMockFactory;
        return $processMockFactory->getMock($this, $exitCode, $output);
    }

    /**
     * Creates process factory mock.
     *
     * @param Process $process Symfony process to inject.
     *
     * @SuppressWarnings(PHPMD.LongVariableName)
     *
     * @return ProcessFactory|Mock
     * @since 0.1.0
     */
    protected function createProcessFactoryMock(Process $process)
    {
        $processFactoryMockFactory = new ProcessFactoryMockFactory;
        return $processFactoryMockFactory->getMock($this, $process);
    }
    
    /**
     * Returns new test instance.
     *
     * @param ProcessFactory $processFactory Process factory to inject.
     *
     * @return FileLocatorInterface
     * @since 0.1.0
     */
    protected function createTestInstance($processFactory = null)
    {
        if (!$processFactory) {
            $processMock = $this->createProcessMock();
            $processFactory = $this->createProcessFactoryMock($processMock);
        }
        $class = $this->getTestedClassName();
        return new $class($processFactory);
    }
}
