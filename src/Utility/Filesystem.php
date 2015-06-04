<?php

namespace Etki\Testing\AllureFramework\Runner\Utility;

use Etki\Testing\AllureFramework\Runner\Exception\Utility\Filesystem\TemporaryNodeCreationException;
use Etki\Testing\AllureFramework\Runner\Utility\PhpApi\Filesystem
    as FilesystemApi;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem as SymfonyFilesystem;

/**
 * Utility filesystem helper class. Most of the time serves just as a simple
 * wrapper for Symfony Filesystem component and PHP filesystem API.
 *
 * todo replace UuidFactory with RandomGeneratorInterface
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Utility
 * @author  Etki <etki@etki.name>
 */
class Filesystem
{
    /**
     * Number of temporary directory creation attempts to be made before
     * throwing an exception.
     *
     * @since 0.1.0
     */
    const MAXIMUM_NODE_CREATION_ATTEMPTS = 5;
    /**
     * Filesystem API.
     *
     * @type FilesystemApi
     * @since 0.1.0
     */
    private $filesystemApi;
    /**
     * Symfony filesystem component.
     *
     * @type SymfonyFilesystem
     * @since 0.1.0
     */
    private $symfonyFilesystem;
    /**
     * UUID generator.
     *
     * @type UuidFactory
     * @since 0.1.0
     */
    private $uuidFactory;

    /**
     * Initializer.
     *
     * @param FilesystemApi     $filesystemApi     PHP filesystem API.
     * @param SymfonyFilesystem $symfonyFilesystem Symfony Filesystem component.
     * @param UuidFactory       $uuidFactory       UUID generator.
     *
     * @codeCoverageIgnore
     *
     * @return self
     * @since 0.1.0
     */
    public function __construct(
        FilesystemApi $filesystemApi,
        SymfonyFilesystem $symfonyFilesystem,
        UuidFactory $uuidFactory
    ) {
        $this->filesystemApi = $filesystemApi;
        $this->symfonyFilesystem = $symfonyFilesystem;
        $this->uuidFactory = $uuidFactory;
    }

    /**
     * Normalizes path.
     *
     * @param string $path             Path to normalize.
     * @param string $currentDirectory Current working directory, if provided
     *                                 path is relative.
     *
     * @SuppressWarnings(PHPMD.LongVariableName)
     *
     * @return string Normalized path.
     * @since 0.1.0
     */
    public function normalizePath($path, $currentDirectory = null)
    {
        $separator = DIRECTORY_SEPARATOR;
        if ($currentDirectory) {
            $path = $currentDirectory . $separator . $path;
        }
        $multipleSeparatorPattern = sprintf('~(?<!:)%s+~', $separator);
        $currentDirectoryPattern = sprintf('~%s.%s~', $separator, $separator);
        $parentDirectoryPattern = sprintf(
            '~%s[^%s]+%s..%s~',
            $separator,
            $separator,
            $separator,
            $separator
        );
        $normalizedPath = preg_replace(
            $multipleSeparatorPattern,
            $separator,
            $path
        );
        $normalizedPath = preg_replace(
            $currentDirectoryPattern,
            $separator,
            $normalizedPath
        );
        $normalizedPath = preg_replace(
            $parentDirectoryPattern,
            $separator,
            $normalizedPath
        );
        return $normalizedPath;
    }

    /**
     * Tells if target at `$path` is executable.
     *
     * @param string $path Path to check.
     *
     * @codeCoverageIgnore
     *
     * @return bool
     * @since 0.1.0
     */
    public function isExecutable($path)
    {
        return $this->filesystemApi->isExecutable($path);
    }

    /**
     * Retrieves system temporary directory location.
     *
     * @codeCoverageIgnore
     *
     * @return string
     * @since 0.1.0
     */
    public function getTemporaryDirectory()
    {
        return $this->filesystemApi->getTemporaryDirectory();
    }

    /**
     * Generates temporary file.
     *
     * @param string $directory Directory path. System temporary directory will
     *                          be used by default.
     * @param string $prefix    File prefix.
     *
     * @return string
     * @since 0.1.0
     */
    public function createTemporaryFile($directory = null, $prefix = '')
    {
        $directory = $directory ?: $this->getTemporaryDirectory();
        $limit = self::MAXIMUM_NODE_CREATION_ATTEMPTS;
        // Of course i'm paranoid enough to believe there will be a collision!
        // Some things are hard to overwhelm.
        for ($attempts = 0; $attempts < $limit; $attempts++) {
            // sadly, this chunk calls for tempnam that doesn't support VFS.
            //$path = $this->filesystemApi->createTemporaryFile(
            //    $directory,
            //    $prefix
            //);
            $uuid = $this->uuidFactory->uuid4();
            $path = $directory . DIRECTORY_SEPARATOR . $prefix . $uuid;
            // todo not atomic
            if (!$this->symfonyFilesystem->exists($path)) {
                $this->symfonyFilesystem->touch($path);
                return $path;
            }
        }
        $message = sprintf(
            'Failed to create temporary file in `%d` attempts',
            $attempts
        );
        throw new TemporaryNodeCreationException($message);
    }

    /**
     * Creates temporary directory.
     *
     * @param string $prefix Optional directory prefix.
     *
     * @return string
     * @since 0.1.0
     */
    public function createTemporaryDirectory($prefix = '')
    {
        $temporaryDirectory = $this->getTemporaryDirectory();
        $limit = self::MAXIMUM_NODE_CREATION_ATTEMPTS;
        for ($attempts = 0; $attempts < $limit; $attempts++) {
            $uuid = $this->uuidFactory->uuid4();
            $path = $temporaryDirectory . DIRECTORY_SEPARATOR . $prefix . $uuid;
            // todo not atomic
            if (!$this->exists($path)) {
                try {
                    $this->symfonyFilesystem->mkdir($path);
                    return $path;
                } catch (IOException $e) {
                    // noop
                }
            }
        }
        $message = sprintf(
            'Failed to create temporary directory in `%d` attempts',
            $attempts
        );
        throw new TemporaryNodeCreationException($message);
    }

    /**
     * Removes whatever is located at `$path`.
     *
     * @param string $path Path to remove.
     *
     * @codeCoverageIgnore
     *
     * @return void
     * @since 0.1.0
     */
    public function remove($path)
    {
        $this->symfonyFilesystem->remove($path);
    }

    /**
     * Renames `$source` file to `$target`.
     *
     * @param string $source    Source file.
     * @param string $target    Target file.
     * @param bool   $overwrite Whether to overwrite existing files or not.
     *
     * @codeCoverageIgnore
     *
     * @return void
     * @since 0.1.0
     */
    public function move($source, $target, $overwrite = true)
    {
        $this->symfonyFilesystem->rename($source, $target, $overwrite);
    }

    /**
     * Tells if filesystem node exists.
     *
     * @param string $path Path to node.
     *
     * @codeCoverageIgnore
     *
     * @return bool
     * @since 0.1.0
     */
    public function exists($path)
    {
        return $this->symfonyFilesystem->exists($path);
    }

    /**
     * Creates directory.
     *
     * @param string $path Directory path.
     *
     * @codeCoverageIgnore
     *
     * @return void
     * @since 0.1.0
     */
    public function createDirectory($path)
    {
        $this->symfonyFilesystem->mkdir($path);
    }

    /**
     * Creates file at `$path`.
     *
     * @param string $path File path.
     *
     * @codeCoverageIgnore
     *
     * @return void
     * @since 0.1.0
     */
    public function createFile($path)
    {
        $this->symfonyFilesystem->touch($path);
    }

    /**
     * Tells if provided path is absolute.
     *
     * @param string $path Path to check.
     *
     * @codeCoverageIgnore
     *
     * @return bool
     * @since 0.1.0
     */
    public function isAbsolutePath($path)
    {
        return $this->symfonyFilesystem->isAbsolutePath($path);
    }

    /**
     * Reads fil contents.
     *
     * @param string $path Path to file.
     *
     * @codeCoverageIgnore
     *
     * @return string File contents.
     * @since 0.1.0
     */
    public function readFile($path)
    {
        return $this->filesystemApi->readFile($path);
    }

    /**
     * Writes file to disk.
     *
     * @param string $path    Path to file.
     * @param string $content New file contents.
     *
     * @codeCoverageIgnore
     *
     * @return int
     * @since 0.1.0
     */
    public function writeFile($path, $content)
    {
        return $this->filesystemApi->writeFile($path, $content);
    }
}
