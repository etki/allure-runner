<?php

namespace Etki\Testing\AllureFramework\Runner\Utility\Filesystem;

use Etki\Testing\AllureFramework\Runner\Exception\Utility\Filesystem\NonexistentTemporaryNodeException;
use Etki\Testing\AllureFramework\Runner\Utility\Filesystem;

/**
 * This class handles all work with temporary files and directories and allows
 * to clean things up in one call.
 *
 * @SuppressWarnings(PHPMD.TooManyMethods)
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Utility
 * @author  Etki <etki@etki.name>
 */
class TemporaryNodesManager
{
    /**
     * File node type.
     *
     * @since 0.1.0
     */
    const NODE_TYPE_FILE = 'file';
    /**
     * Directory node type.
     *
     * @since 0.1.0
     */
    const NODE_TYPE_DIRECTORY = 'directory';
    /**
     * Filesystem helper.
     *
     * @type Filesystem
     * @since 0.1.0
     */
    private $filesystem;
    /**
     * List of registered nodes in [path => type] format.
     *
     * @type string[]
     * @since 0.1.0
     */
    private $nodes = array();

    /**
     * Initializer.
     *
     * @param Filesystem $filesystem Filesystem helper.
     *
     * @return self
     * @since 0.1.0
     */
    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * Creates temporary file and returns it's path
     *
     * @param string $prefix Optional file prefix.
     *
     * @return string
     * @since 0.1.0
     */
    public function createTemporaryFile($prefix = '')
    {
        $path = $this->filesystem->createTemporaryFile(
            $this->filesystem->getTemporaryDirectory(),
            $prefix
        );
        $this->nodes[$path] = self::NODE_TYPE_FILE;
        return $path;
    }

    /**
     * Creates temporary directory and returns it's path.
     *
     * @param string $prefix Optional directory prefix.
     *
     * @return string
     * @since 0.1.0
     */
    public function createTemporaryDirectory($prefix = '')
    {
        $path = $this->filesystem->createTemporaryDirectory($prefix);
        $this->nodes[$path] = self::NODE_TYPE_DIRECTORY;
        return $path;
    }

    /**
     * Retrieves list of temporary files.
     *
     * @return string[]
     * @since 0.1.0
     */
    public function getTemporaryFiles()
    {
        return $this->getNodes(self::NODE_TYPE_FILE);
    }

    /**
     * Retrieves list of temporary directories.
     *
     * @return string[]
     * @since 0.1.0
     */
    public function getTemporaryDirectories()
    {
        return $this->getNodes(self::NODE_TYPE_DIRECTORY);
    }

    /**
     * Removes temporary file.
     *
     * @param string $path Path to temporary file.
     *
     * @throws NonexistentTemporaryNodeException Thrown in case such temporary
     *                                           file isn't registered.
     * @return void
     * @since 0.1.0
     */
    public function removeTemporaryFile($path)
    {
        $this->assertNodeExists($path, self::NODE_TYPE_FILE);
        $this->removeNode($path);
    }

    /**
     * Removes temporary directory.
     *
     * @param string $path Path to temporary directory.
     *
     * @throws NonexistentTemporaryNodeException Thrown in case such temporary
     *                                           directory isn't registered.
     *
     * @return void
     * @since 0.1.0
     */
    public function removeTemporaryDirectory($path)
    {
        $this->assertNodeExists($path, self::NODE_TYPE_DIRECTORY);
        $this->removeNode($path);
    }

    /**
     * Removes all temporary directories.
     *
     * @return void
     * @since 0.1.0
     */
    public function removeTemporaryDirectories()
    {
        $this->removeNodes($this->getTemporaryDirectories());
    }

    /**
     * Removes all registered temporary files.
     *
     * @return void
     * @since 0.1.0
     */
    public function removeTemporaryFiles()
    {
        $this->removeNodes($this->getTemporaryFiles());
    }

    /**
     * Removes all registered temporary nodes.
     *
     * @return void
     * @since 0.1.0
     */
    public function removeTemporaryNodes()
    {
        $this->removeNodes($this->nodes);
    }

    /**
     * Retrieves nodes of specific type.
     *
     * @param string $type Node type.
     *
     * @return string[]
     * @since 0.1.0
     */
    private function getNodes($type)
    {
        $nodes = array();
        // because i don't like anonymous functions
        foreach ($this->nodes as $node => $nodeType) {
            if ($nodeType === $type) {
                $nodes[] = $node;
            }
        }
        return $nodes;
    }

    /**
     * Removes list of nodes.
     *
     * @param string[] $list List of node paths.
     *
     * @return void
     * @since 0.1.0
     */
    private function removeNodes(array $list)
    {
        foreach ($list as $path) {
            $this->removeNode($path);
        }
    }

    /**
     * Removes single node.
     *
     * @param string $path Node path.
     *
     * @return void
     * @since 0.1.0
     */
    private function removeNode($path)
    {
        $this->filesystem->remove($path);
        unset($this->nodes[$path]);
    }

    /**
     * Verifies that temporary node exists and has correct type and throws
     * exception if anything is wrong.
     *
     * @param string $path Node path.
     * @param string $type Node type.
     *
     * @throws NonexistentTemporaryNodeException
     *
     * @return void
     * @since 0.1.0
     */
    private function assertNodeExists($path, $type = null)
    {
        if (!isset($this->nodes[$path])
            || ($type && $this->nodes[$path] !== $type)
        ) {
            $exception = $this->createNonexistentNodeException(
                $path,
                self::NODE_TYPE_FILE
            );
            throw $exception;
        }
    }

    /**
     * Creates nonexistent filesystem node exception.
     *
     * @param string $nodePath     Node path.
     * @param string $expectedType Expected node type (file/directory).
     *
     * @return NonexistentTemporaryNodeException
     * @since 0.1.0
     */
    private function createNonexistentNodeException($nodePath, $expectedType)
    {
        $message = sprintf(
            'Nonexsitent node `%s` of type `%s` requested. Please verify that' .
            'path and type are used correctly.',
            $nodePath,
            $expectedType
        );
        return new NonexistentTemporaryNodeException($message);
    }
}
