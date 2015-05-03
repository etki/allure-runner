<?php

namespace Etki\Testing\AllureFramework\Runner\Tests\Support\Mock\Factory;

use BadMethodCallException;
use Codeception\TestCase\Test;
use PHPUnit_Framework_MockObject_MockBuilder as MockBuilder;
use PHPUnit_Framework_MockObject_MockObject as Mock;
use ReflectionClass;
use ReflectionMethod;

/**
 * Base mock factory.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Tests\Support\Mock\Factory
 * @author  Etki <etki@etki.name>
 */
abstract class AbstractMockFactory
{
    /**
     * Mocked class reflection.
     *
     * @type ReflectionClass
     * @since 0.1.0
     */
    private $mockedClassReflection;
    /**
     * List of methods of mocked class.
     *
     * @type ReflectionMethod[]
     * @since 0.1.0
     */
    private $mockedClassMethods;
    /**
     * Current test.
     *
     * @type Test
     * @since 0.1.0
     */
    private $test;
    
    /**
     * Returns class FQCN.
     *
     * @return string
     * @since 0.1.0
     */
    abstract public function getMockedClass();

    /**
     * Creates mock builder.
     *
     * @param string[] $methods List of methods to override.
     *
     * @return MockBuilder
     * @since 0.1.0
     */
    protected function getMockBuilder(array $methods = null)
    {
        $builder = $this->getTest()->getMockBuilder($this->getMockedClass());
        if ($methods) {
            $builder->setMethods($methods);
        }
        return $builder;
    }

    /**
     * Retrieves mocked class reflection.
     *
     * @return ReflectionClass
     * @since 0.1.0
     */
    protected function getMockedClassReflection()
    {
        if (!isset($this->mockedClassReflection)) {
            $this->mockedClassReflection
                = new ReflectionClass($this->getMockedClass());
        }
        return $this->mockedClassReflection;
    }

    /**
     * Retrieves methods of class being mocked.
     *
     * @return ReflectionMethod[]
     * @since 0.1.0
     */
    protected function getMockedClassMethods()
    {
        if (!isset($this->mockedClassMethods)) {
            $this->mockedClassMethods
                = $this->getMockedClassReflection()->getMethods();
        }
        return $this->mockedClassMethods;
    }

    /**
     * Filters current class methods.
     *
     * @param int $filter Filter mask
     *
     * @return ReflectionMethod[]
     * @since 0.1.0
     */
    protected function getFilteredMockedClassMethods($filter)
    {
        $methods = array();
        foreach ($this->getMockedClassMethods() as $method) {
            if ($method->getModifiers() & $filter) {
                $methods[] = $method;
            }
        }
        return $methods;
    }

    /**
     * Retrieves list of public methods.
     *
     * @return ReflectionMethod[]
     * @since 0.1.0
     */
    protected function getMockedClassPublicMethods()
    {
        $filter = ReflectionMethod::IS_PUBLIC;
        return $this->getFilteredMockedClassMethods($filter);
    }

    /**
     * Creates and returns prepared mock builder.
     *
     * @param array $arguments Conttructor arguments.
     *
     * @return MockBuilder
     * @since 0.1.0
     */
    protected function getPreparedMockBuilder(array $arguments = array())
    {
        $reflection = new ReflectionClass($this->getMockedClass());
        $filter = ReflectionMethod::IS_PUBLIC & ~ReflectionMethod::IS_STATIC;
        $methods = $reflection->getMethods($filter);
        $methodNames = array();
        foreach ($methods as $method) {
            $methodNames[] = $method->name;
        }
        $builder = $this->getMockBuilder($methodNames);
        if ($arguments) {
            $builder->setConstructorArgs($arguments);
        }
        return $builder;
    }

    /**
     * Retrieves mock.
     *
     * @param array $arguments List of constructor arguments.
     *
     * @return Mock
     * @since 0.1.0
     */
    public function getMock(array $arguments = array())
    {
        return $this->getPreparedMockBuilder($arguments)->getMock();
    }

    /**
     * Returns mock that doesn't call constructor.
     *
     * @param Test $test Currently run test.
     *
     * @return Mock
     * @since 0.1.0
     */
    public function getConstructorlessMock()
    {
        $builder = $this->getPreparedMockBuilder();
        $builder->disableOriginalConstructor();
        return $builder->getMock();
    }

    /**
     * Gets dummy mock that simply returns null wherever it wants.
     *
     * @param array $arguments List of constructor arguments.
     *
     * @return Mock
     * @since 0.1.0
     */
    public function getDummyMock(array $arguments = array())
    {
        $mock = $this->getMock($arguments);
        foreach ($this->getMockedClassPublicMethods() as $method) {
            $mock
                ->expects($this->getTest()->any())
                ->method($method->name)
                ->willReturn(null);
        }
        return $mock;
    }

    /**
     * Sets current test.
     *
     * @param Test $test Currently run test.
     *
     * @return $this Current instance;
     * @since 0.1.0
     */
    public function setTest(Test $test)
    {
        $this->test = $test;
        return $this;
    }

    /**
     * Retrieves current test.
     *
     * @return Test
     * @since 0.1.0
     */
    protected function getTest()
    {
        if (!$this->test) {
            throw new BadMethodCallException('Test instance hasn\'t been set');
        }
        return $this->test;
    }
}
