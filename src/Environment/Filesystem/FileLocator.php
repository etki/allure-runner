<?php

namespace Etki\Testing\AllureFramework\Runner\Environment\Filesystem;

use Etki\Testing\AllureFramework\Runner\Environment\Filesystem\FileLocatorCommandProviderFactory
    as CommandProviderFactory;
use Etki\Testing\AllureFramework\Runner\Environment\ProcessFactory;
use Etki\Testing\AllureFramework\Runner\Exception\RuntimeException;

/**
 * Generic file locator for UNIX-base systems.
 *
 * ---
 *
 * Yet another stupid class with gigantic names.
 * 
 * todo introduce new CommandTemplatesNotProvided exception?
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Environment\Filesystem
 * @author  Etki <etki@etki.name>
 */
class FileLocator
{
    /**
     * Instance of executable location command templates provider.
     *
     * @type ExecutableLocationCommandsProviderInterface
     * @since 0.1.0
     */
    private $executableLocationCommandTemplateProvider;
    /**
     * Instance of file location command templates provider.
     *
     * @type FileLocationCommandsProviderInterface
     * @since 0.1.0
     */
    private $fileLocationCommandTemplateProvider;
    /**
     * Process factory.
     *
     * @type ProcessFactory
     * @since 0.1.0
     */
    private $processFactory;

    /**
     * Initializer.
     *
     * @param CommandProviderFactory $commandProviderFactory Generates command
     *                                                       template providers.
     * @param ProcessFactory         $processFactory         Process factory.
     *
     * @SuppressWarnings(PHPMD.LongVariableName)
     *
     * @return self
     * @since 0.1.0
     */
    public function __construct(
        CommandProviderFactory $commandProviderFactory,
        ProcessFactory $processFactory
    ) {
        $this->executableLocationCommandTemplateProvider
            = $commandProviderFactory->getExecutableLocationCommandTemplatesProvider();
        $this->fileLocationCommandTemplateProvider
            = $commandProviderFactory->getFileLocationCommandTemplatesProvider();
        $this->processFactory = $processFactory;
    }
    /**
     * Locates executable file.
     *
     * @param string $name Executable file name.
     *
     * @return string[]|null List of paths to executables or null in case of
     *                       failure.
     * @since 0.1.0
     */
    public function locateExecutable($name)
    {
        $commandTemplates = $this
            ->executableLocationCommandTemplateProvider
            ->getExecutableLocationCommandTemplates();
        if (!$commandTemplates) {
            $message = 'No commands provided for executable file search. You ' .
                'should specify all Allure files in your configuration.';
            throw new RuntimeException($message);
        }
        return $this->locate($name, $commandTemplates);
    }

    /**
     * Locates file by it's name.
     *
     * @param string $name Name of the file.
     *
     * @return string[]|null List of paths to matching files or null in case of
     * failure.
     * @since 0.1.0
     */
    public function locateFile($name)
    {
        $commandTemplates = $this
            ->fileLocationCommandTemplateProvider
            ->getFileLocationCommandTemplates();
        if (!$commandTemplates) {
            $message = 'No commands provided for exact file search. Most ' .
                'probably your OS doesn\'t provide indexed file search at ' .
                'all, so you need to specify all Allure executable files ' .
                '(allure / allure.bat / allure jar & java) in your ' .
                'configuration.';
            throw new RuntimeException($message);
        }
        return $this->locate($name, $commandTemplates);
    }

    /**
     * Locates file using provided command templates.
     *
     * @param string   $name             Name of the file.
     * @param string[] $commandTemplates List of command templates.
     *
     * @return string[]|null
     * @since 0.1.0
     */
    private function locate($name, array $commandTemplates)
    {
        if (!$commandTemplates) {
            $message = 'No commands provided for this operation on this OS';
            throw new RuntimeException($message);
        }
        foreach ($commandTemplates as $commandTemplate) {
            $command = sprintf($commandTemplate, $name);
            if ($result = $this->tryLocate($command)) {
                return $result;
            }
        }
        return null;
    }

    /**
     * Tries to locate file using provided `$command`.
     *
     * @param string $command Command to run.
     *
     * @return string[]|null List of entries or null on failure.
     * @since 0.1.0
     */
    private function tryLocate($command)
    {
        $process = $this->processFactory->getProcess($command);
        // means command returned anything but not 0 exit code.
        if ($process->run()) {
            return null;
        }
        return $this->processOutput($process->getOutput());
    }

    /**
     * Splits command output into entries.
     *
     * @param string $output Output to process.
     *
     * @return string[] Returned entries.
     * @since 0.1.0
     */
    private function processOutput($output)
    {
        $lines = explode("\n", $output);
        $entries = array_filter(array_map('trim', $lines));
        return $entries;
    }
}
