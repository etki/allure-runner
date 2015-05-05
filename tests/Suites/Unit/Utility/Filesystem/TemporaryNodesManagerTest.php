<?php

namespace Etki\Testing\AllureFramework\Runner\Tests\Unit\Utility\Filesystem;

use Doctrine\Common\Annotations\Annotation\IgnoreAnnotation;
use Etki\Testing\AllureFramework\Runner\Utility\Filesystem;
use Etki\Testing\AllureFramework\Runner\Utility\Filesystem\TemporaryNodesManager;
use Etki\Testing\AllureFramework\Runner\Tests\Support\Test\AbstractClassAwareTest;
use UnitTester;
use PHPUnit_Framework_MockObject_MockObject as Mock;

/**
 * Tests temporary files and directories manager.
 *
 * @IgnoreAnnotation("expectedException")
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Tests\Unit\Utility\Filesystem
 * @author  Etki <etki@etki.name>
 */
class TemporaryNodesManagerTest extends AbstractClassAwareTest
{
    /**
     * Test subject FQCN.
     *
     * @since 0.1.0
     */
    const TESTED_CLASS
        = 'Etki\Testing\AllureFramework\Runner\Utility\Filesystem\TemporaryNodesManager';
    /**
     * Filesystem helper FQCN.
     *
     * @since 0.1.0
     */
    const FILESYSTEM_HELPER_CLASS
        = 'Etki\Testing\AllureFramework\Runner\Utility\Filesystem';
    /**
     * Tester instance.
     *
     * @type UnitTester
     * @since 0.1.0
     */
    protected $tester;
    
    // utility methods

    /**
     * Returns test subject FQCN.
     *
     * @return string
     * @since 0.1.0
     */
    public function getTestedClass()
    {
        return self::TESTED_CLASS;
    }

    /**
     * Creates test instance.
     *
     * @param Filesystem $filesystem FilesystemHelper instance
     *
     * @return TemporaryNodesManager
     * @since 0.1.0
     */
    protected function createTestInstance(Filesystem $filesystem = null)
    {
        if (!$filesystem) {
            $filesystem = $this->createFilesystemMock();
        }
        return parent::createTestInstance($filesystem);
    }

    /**
     * Creates filesystem mock.
     *
     * @param int $createFileCount      How many times `createTemporaryFile()`
     *                                  should be called.
     * @param int $createDirectoryCount How many times
     *                                  `createTemporaryDirectory()` should be
     *                                  called.
     * @param int $removeCount          How many times `remove()` should be
     *                                  called.
     *
     * @return Filesystem|Mock
     * @since 0.1.0
     */
    protected function createFilesystemMock(
        $createFileCount = null,
        $createDirectoryCount = null,
        $removeCount = null
    ) {
        $counts = array(
            'createTemporaryDirectory' => $createDirectoryCount,
            'createTemporaryFile' => $createFileCount,
            'remove' => $removeCount,
        );
        $filesystem = $this
            ->getMockFactory(self::FILESYSTEM_HELPER_CLASS)
            ->getConstructorlessMock();
        foreach ($counts as $method => $count) {
            $constraint = $count ? $this->exactly($count) : $this->any();
            $filesystem
                ->expects($constraint)
                ->method($method)
                ->willReturnCallback(
                    function () {
                        return md5(rand(0, PHP_INT_MAX));
                    }
                );
        }
        return $filesystem;
    }

    // tests

    /**
     * Tests work with temporary files.
     *
     * @return void
     * @since 0.1.0
     */
    public function testFileManagement()
    {
        $limit = 5;
        $filesystem = $this->createFilesystemMock($limit, 0, $limit);
        $instance = $this->createTestInstance($filesystem);
        $this->assertEmpty($instance->getTemporaryFiles());
        $files = array();
        for ($i = 0; $i < 5; $i++) {
            $files[] = $instance->createTemporaryFile();
        }
        $this->assertCount($limit, $instance->getTemporaryFiles());
        $this->assertContains($files[0], $instance->getTemporaryFiles());
        $instance->removeTemporaryFile($files[0]);
        $this->assertCount($limit - 1, $instance->getTemporaryFiles());
        $this->assertNotContains($files[0], $instance->getTemporaryFiles());
        $instance->removeTemporaryFiles();
        $this->assertCount(0, $instance->getTemporaryFiles());
    }

    /**
     * Tests work with temporary directories.
     *
     * @return void
     * @since 0.1.0
     */
    public function testDirectoryManagement()
    {
        $limit = 5;
        $filesystem = $this->createFilesystemMock(0, $limit, $limit);
        $instance = $this->createTestInstance($filesystem);
        $this->assertEmpty($instance->getTemporaryDirectories());
        $directories = array();
        for ($i = 0; $i < 5; $i++) {
            $directories[] = $instance->createTemporaryDirectory();
        }
        $this->assertCount($limit, $instance->getTemporaryDirectories());
        $this->assertContains(
            $directories[0],
            $instance->getTemporaryDirectories()
        );
        $instance->removeTemporaryDirectory($directories[0]);
        $this->assertCount($limit - 1, $instance->getTemporaryDirectories());
        $this->assertNotContains(
            $directories[0],
            $instance->getTemporaryDirectories()
        );
        $instance->removeTemporaryDirectories();
        $this->assertCount(0, $instance->getTemporaryDirectories());
    }

    /**
     * Verifies that exception will be thrown
     *
     * @expectedException \Etki\Testing\AllureFramework\Runner\Exception\Utility\Filesystem\NonexistentTemporaryNodeException
     *
     * @return void
     * @since 0.1.0
     */
    public function testInvalidAccessReaction()
    {
        $this->createTestInstance()->removeTemporaryFile('nonexistent');
    }
}
