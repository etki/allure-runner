<?php

namespace Etki\Testing\AllureFramework\Runner\Tests\Unit\Utility;

use BadMethodCallException;
use Etki\Testing\AllureFramework\Runner\Utility\Autoloader;
use Etki\Testing\AllureFramework\Runner\Tests\Support\Data\FullyQualifiedClassName;
use Etki\Testing\AllureFramework\Runner\Tests\Support\Data\Generator\RandomFullyQualifiedClassNameGenerator;
use Doctrine\Common\Annotations\Annotation\IgnoreAnnotation;
use Symfony\Component\Filesystem\Filesystem;
use VirtualFileSystem\FileSystem as VFS;
use Codeception\TestCase\Test;
use UnitTester;
use VirtualFileSystem\Structure\Node;

/**
 * Tests embedded autoloader class.
 *
 * @IgnoreAnnotation("expectedException")
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Tests\Unit\Utility
 * @author  Etki <etki@etki.name>
 */
class AutoloaderTest extends Test
{
    /**
     * Tested class FQCN.
     *
     * @since 0.1.0
     */
    const TESTED_CLASS
        = '\Etki\Testing\AllureFramework\Runner\Utility\Autoloader';
    /**
     * Template for dummy class file.
     *
     * @since 0.1.0
     */
    const CLASS_FILE_TEMPLATE = '<?php namespace %s; class %s {}';
    /**
     * Tester instance.
     *
     * @type UnitTester
     * @since 0.1.0
     */
    protected $tester;
    /**
     * List of detached autoloaders.
     *
     * @type callable[]
     * @since 0.1.0
     */
    private $autoloaderStack = array();
    /**
     * FQCN of existing class.
     *
     * @type FullyQualifiedClassName
     * @since 0.1.0
     */
    private $existingClassFqcn;
    /**
     * FQCN of inexisting class.
     *
     * @type FullyQualifiedClassName
     * @since 0.1.0
     */
    private $inexistingClassFqcn;
    /**
     * VFS handle instance.
     *
     * @type VFS
     * @since 0.1.0
     */
    private $vfs;
    
    // utility methods

    /**
     * Creates test instance.
     *
     * @return Autoloader
     * @since 0.1.0
     */
    private function createTestInstance()
    {
        $class = self::TESTED_CLASS;
        return new $class;
    }

    // @codingStandardsIgnoreStart

    /**
     * Before-test initialization hook.
     * 
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     *
     * @return void
     * @since 0.1.0
     */
    protected function _before()
    {
        $fqcnGenerator = new RandomFullyQualifiedClassNameGenerator;
        $this->existingClassFqcn = $fqcnGenerator->generate(5);
        $this->inexistingClassFqcn = $fqcnGenerator->generate(3);
        $this->vfs = new VFS;
        $fqcn = $this->existingClassFqcn;;
        $path = str_replace('\\', '/', $fqcn->getAbsoluteFqcn()) . '.php';
        $vfsPath = $this->vfs->path($path);
        $filesystem = new Filesystem;
        $filesystem->mkdir(dirname($vfsPath));
        $namespace = $fqcn->getNamespace();
        $className = $fqcn->getClassName();
        $contents = sprintf(self::CLASS_FILE_TEMPLATE, $namespace, $className);
        file_put_contents($vfsPath, $contents);
    }

    /**
     * After-test cleanup hook.
     *
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     *
     * @return void
     * @since 0.1.0
     */
    protected function _after()
    {
        /** @type Node $child */
        foreach ($this->vfs->root()->children() as $child) {
            $this->vfs->root()->remove($child->basename());
        }
        unset($this->existingClassFqcn, $this->inexistingClassFqcn, $this->vfs);
    }

    // @codingStandardsIgnoreEnd

    /**
     * Detaches all currently registered autoloaders.
     *
     * @return void
     * @since 0.1.0
     */
    private function detachAutoloaders()
    {
        $autoloaders = spl_autoload_functions();
        if ($autoloaders) {
            foreach ($autoloaders as $autoloader) {
                spl_autoload_unregister($autoloader);
                $this->autoloaderStack[] = $autoloader;
            }
        }
    }

    /**
     * Reattaches autoloaders.
     *
     * @return void
     * @since 0.1.0
     */
    private function attachAutoloaders()
    {
        foreach ($this->autoloaderStack as $autoloader) {
            spl_autoload_register($autoloader);
        }
    }

    // tests

    /**
     * Tests if autoloading works.
     *
     * @SuppressWarnings(PHPMD.LongVariableName)
     *
     * @return void
     * @since 0.1.0
     */
    public function testAutoloading()
    {
        $this->assertFalse(class_exists($this->inexistingClassFqcn));
        $this->assertFalse(class_exists($this->existingClassFqcn));

        $autoloader = $this->createTestInstance();
        $callback = array($autoloader, 'loadClass');
        $autoloader->registerNamespace(
            $this->existingClassFqcn->getRootNamespace(),
            $this->vfs->path('/' . $this->existingClassFqcn->getRootNamespace())
        );
        
        $this->detachAutoloaders();
        
        spl_autoload_register($callback);
        
        $existingClassExists = class_exists($this->existingClassFqcn);
        $inexistingClassExists = class_exists($this->inexistingClassFqcn);
        
        spl_autoload_unregister($callback);
        
        $this->attachAutoloaders();
        $this->assertTrue($existingClassExists);
        $this->assertFalse($inexistingClassExists);
    }

    /**
     * Verifies that incorrect directories are correctly handled.
     *
     * @expectedException BadMethodCallException
     *
     * @return void
     * @since 0.1.0
     */
    public function testInvalidNamespaceRegistration()
    {
        $this->createTestInstance()->registerNamespace('Dummy', md5(uniqid()));
    }
}
