<?php

namespace Etki\Testing\AllureFramework\Runner\Configuration;

use Etki\Testing\AllureFramework\Runner\IO\PrefixAwareIOControllerInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Allure runner configuration.
 *
 * @codeCoverageIgnore
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Configuration
 * @author  Etki <etki@etki.name>
 */
class Configuration
{
    /**
     * Report version used by default.
     *
     * @since 0.1.0
     */
    const DEFAULT_REPORT_VERSION = '1.4.5';
    /**
     * Path to container configuration file.
     *
     * @since 0.1.0
     */
    const CONTAINER_CONFIGURATION_FILE_NAME = 'container.yml';
    /**
     * Username (orgname) of the corresponding repository owner on github.
     *
     * @since 0.1.0
     */
    const GITHUB_REPOSITORY_OWNER = 'allure-framework';
    /**
     * Name of the corresponding repository on github.
     *
     * @since 0.1.0
     */
    const GITHUB_REPOSITORY_NAME = 'allure-cli';
    /**
     * Exit code used for errors.
     *
     * @since 0.1.0
     */
    const GENERIC_ERROR_EXIT_CODE = 127;
    /**
     * This flag enables whole processing and allows to switch extension off
     * by setting a single flag.
     *
     * @type bool
     * @since 0.1.0
     */
    private $enabled = false;
    /**
     * List of report sources.
     *
     * @type string[]
     * @since 0.1.0
     */
    private $sources = array();
    /**
     * Directory where output should be generated.
     *
     * @type string
     * @since 0.1.0
     */
    private $reportPath;
    /**
     * Report version to use.
     *
     * @type string
     * @since 0.1.0
     */
    private $reportVersion = self::DEFAULT_REPORT_VERSION;
    /**
     * Path to Allure executable (not .jar file).
     *
     * @type string
     * @since 0.1.0
     */
    private $executable;
    /**
     * Path to allure .jar file.
     *
     * @type string
     * @since 0.1.0
     */
    private $jar;
    /**
     * Verbosity level for I\O controllers.
     *
     * @type string
     * @since 0.1.0
     */
    private $verbosity = Verbosity::LEVEL_INFO;
    /**
     * Prefix format for logger.
     *
     * @type string
     * @since 0.1.0
     */
    private $outputPrefixFormat
        = PrefixAwareIOControllerInterface::MEDIUM_PREFIX_FORMAT;
    /**
     * Whether to download jar in case it's missing or not.
     *
     * @type bool
     * @since 0.1.0
     */
    private $downloadMissingJar = false;
    /**
     * Preferred Allure CLI version. Has to match release tag on github.
     *
     * @type string
     * @since 0.1.0
     */
    private $preferredAllureVersion = '2.3';
    /**
     * This option tells runner to throw exception whenever executable can't be
     * found.
     *
     * @type bool
     * @since 0.1.0
     */
    private $throwOnMissingExecutable = true;
    /**
     * This option tells runner to throw exception whenever executable returns
     * return code other than zero.
     *
     * todo, currently unsupported
     *
     * @type bool
     * @since 0.1.0
     */
    private $throwOnNonZeroResult = true;
    /**
     * This option tells runner to throw exception if configuration hasn't
     * passed validation.
     *
     * todo, currently unsupported
     *
     * @type bool
     * @since 0.1.0
     */
    private $throwOnInvalidConfiguration = false;
    /**
     * Whether to clean generated files or leave them.
     *
     * todo, currently unsupported
     *
     * @type bool
     * @since 0.1.0
     */
    private $cleanGeneratedFiles = true;
    /**
     * Location of temporary directory.
     *
     * todo, currently unsupported
     *
     * @type string
     * @since 0.1.0
     */
    private $temporaryDirectory;
    /**
     * This flag determines whether runner should use VFS for it's operations or
     * regular disk.
     *
     * todo, currently unsupported
     *
     * @type bool
     * @since 0.1.0
     */
    private $useVfs = false;
    /**
     * Forces dry run.
     *
     * todo, currently unsupported
     *
     * @type bool
     * @since 0.1.0
     */
    private $dryRun = false;

    /**
     * Adds several sources at once.
     *
     * @param string[] $sources List of report data sources.
     *
     * @return void
     * @since 0.1.0
     */
    public function addSources(array $sources)
    {
        // forcing [source => source] format
        $sources = array_combine($sources, $sources);
        $this->sources = array_merge($this->sources, $sources);
    }

    /**
     * Adds single source.
     *
     * @param string $source Source to add.
     *
     * @return void
     * @since 0.1.0
     */
    public function addSource($source)
    {
        $this->sources[$source] = $source;
    }

    /**
     * Removes source from collection.
     *
     * @param string $source Source to remove.
     *
     * @return void
     * @since 0.1.0
     */
    public function removeSource($source)
    {
        unset($this->sources[$source]);
    }

    /**
     * Returns source paths for report generation.
     *
     * @Assert\Count(min="1")
     *
     * @return string[] List of paths to source directories.
     * @since 0.1.0
     */
    public function getSources()
    {
        return array_values($this->sources);
    }

    /**
     * Tells if runner is enabled.
     *
     * @return bool
     * @since 0.1.0
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * Sets enabled flag.
     *
     * @param bool $enabled Flag.
     *
     * @return $this Current instance.
     * @since 0.1.0
     */
    public function setIsEnabled($enabled)
    {
        $this->enabled = (bool) $enabled;
        return $this;
    }

    /**
     * Returns path to executable.
     *
     * @return string
     * @since 0.1.0
     */
    public function getExecutable()
    {
        return $this->executable;
    }

    /**
     * Sets executable.
     *
     * @param string $executable Executable.
     *
     * @return $this Current instance.
     * @since 0.1.0
     */
    public function setExecutable($executable)
    {
        $this->executable = $executable;
        return $this;
    }

    /**
     * Returns .jar file path.
     *
     * @return string
     * @since 0.1.0
     */
    public function getJar()
    {
        return $this->jar;
    }

    /**
     * Sets .jar path.
     *
     * @param string $jar .jar file path.
     *
     * @return $this Current instance.
     * @since 0.1.0
     */
    public function setJar($jar)
    {
        $this->jar = $jar;
        return $this;
    }

    /**
     * Returns report path.
     *
     * @return string
     * @since 0.1.0
     */
    public function getReportPath()
    {
        return $this->reportPath;
    }

    /**
     * Sets report output path.
     *
     * @param string $reportPath Report path.
     *
     * @return $this Current instance.
     * @since 0.1.0
     */
    public function setReportPath($reportPath)
    {
        $this->reportPath = $reportPath;
        return $this;
    }

    /**
     * Returns currently used report version.
     *
     * @return string
     * @since 0.1.0
     */
    public function getReportVersion()
    {
        return $this->reportVersion;
    }

    /**
     * Sets reportVersion.
     *
     * @param string $reportVersion ReportVersion.
     *
     * @return $this Current instance.
     * @since 0.1.0
     */
    public function setReportVersion($reportVersion)
    {
        $this->reportVersion = $reportVersion;
        return $this;
    }

    /**
     * Returns verbosity.
     *
     * @return string
     * @since 0.1.0
     */
    public function getVerbosity()
    {
        return $this->verbosity;
    }

    /**
     * Sets verbosity.
     *
     * @param string $verbosity Verbosity.
     *
     * @return $this Current instance.
     * @since 0.1.0
     */
    public function setVerbosity($verbosity)
    {
        $this->verbosity = $verbosity;
        return $this;
    }

    /**
     * Returns I/O controller output prefix format.
     *
     * @return string
     * @since 0.1.0
     */
    public function getOutputPrefixFormat()
    {
        return $this->outputPrefixFormat;
    }

    /**
     * Sets outputPrefixFormat.
     *
     * @param string $outputPrefixFormat I/O controller output prefix format.
     *
     * @return $this Current instance.
     * @since 0.1.0
     */
    public function setOutputPrefixFormat($outputPrefixFormat)
    {
        $this->outputPrefixFormat = $outputPrefixFormat;
        return $this;
    }

    /**
     * Tells if scenario should try to download missing jar.
     *
     * @return boolean
     * @since 0.1.0
     */
    public function shouldDownloadMissingJar()
    {
        return $this->downloadMissingJar;
    }

    /**
     * Sets `download missing jar` flag.
     *
     * @param boolean $downloadMissingJar Flag value.
     *
     * @return $this Current instance.
     * @since 0.1.0
     */
    public function setDownloadMissingJar($downloadMissingJar)
    {
        $this->downloadMissingJar = $downloadMissingJar;
        return $this;
    }

    /**
     * Returns preferredAllureVersion.
     *
     * @return string
     * @since 0.1.0
     */
    public function getPreferredAllureVersion()
    {
        return $this->preferredAllureVersion;
    }

    /**
     * Sets preferredAllureVersion.
     *
     * @param string $preferredAllureVersion PreferredAllureVersion.
     *
     * @SuppressWarnings(PHPMD.LongVariableName)
     *
     * @return $this Current instance.
     * @since 0.1.0
     */
    public function setPreferredAllureVersion($preferredAllureVersion)
    {
        $this->preferredAllureVersion = $preferredAllureVersion;
        return $this;
    }

    /**
     * Tells if runner should throw an exception on missing executable or simply
     * return bad run result.
     *
     * @return bool
     * @since 0.1.0
     */
    public function shouldThrowOnMissingExecutable()
    {
        return $this->throwOnMissingExecutable;
    }

    /**
     * Sets throwOnMissingExecutable.
     *
     * @param bool $throwOnMissingExecutable Whether runner should or should not
     *                                       throw an exception.
     *
     * @SuppressWarnings(PHPMD.LongVariableName)
     *
     * @return $this Current instance.
     * @since 0.1.0
     */
    public function setThrowOnMissingExecutable($throwOnMissingExecutable)
    {
        $this->throwOnMissingExecutable = $throwOnMissingExecutable;
        return $this;
    }

    /**
     * Tells if runner should throw an exception on non-zero result.
     *
     * @return bool
     * @since 0.1.0
     */
    public function shouldThrowOnNonZeroExitCode()
    {
        return $this->throwOnNonZeroResult;
    }

    /**
     * Sets throwOnNonZeroResult.
     *
     * @param bool $throwOnNonZeroResult ThrowOnNonZeroResult.
     *
     * @return $this Current instance.
     * @since 0.1.0
     */
    public function setThrowOnNonZeroResult($throwOnNonZeroResult)
    {
        $this->throwOnNonZeroResult = $throwOnNonZeroResult;
        return $this;
    }

    /**
     * Tells if an exception should be thrown on invalid configuration.
     *
     * @return bool
     * @since 0.1.0
     */
    public function shouldThrowOnInvalidConfiguration()
    {
        return $this->throwOnInvalidConfiguration;
    }

    /**
     * Sets throwOnInvalidConfiguration.
     *
     * @param bool $throwOnInvalidConfiguration ThrowOnInvalidConfiguration.
     *
     * @SuppressWarnings(PHPMD.LongVariableName)
     *
     * @return $this Current instance.
     * @since 0.1.0
     */
    public function setThrowOnInvalidConfiguration($throwOnInvalidConfiguration)
    {
        $this->throwOnInvalidConfiguration = $throwOnInvalidConfiguration;
        return $this;
    }

    /**
     * Tells if Allure runner should delete it's generated files.
     *
     * @return bool
     * @since 0.1.0
     */
    public function shouldCleanGeneratedFiles()
    {
        return $this->cleanGeneratedFiles;
    }

    /**
     * Sets cleanGeneratedFiles.
     *
     * @param bool $cleanGeneratedFiles CleanGeneratedFiles.
     *
     * @return $this Current instance.
     * @since 0.1.0
     */
    public function setCleanGeneratedFiles($cleanGeneratedFiles)
    {
        $this->cleanGeneratedFiles = (bool) $cleanGeneratedFiles;
        return $this;
    }

//    /**
//     * Returns path to temporary directory.
//     *
//     * @return string
//     * @since 0.1.0
//     */
//    public function getTemporaryDirectory()
//    {
//        return $this->temporaryDirectory;
//    }
//
//    /**
//     * Sets temporary directory.
//     *
//     * @param string $temporaryDirectory Path to temporary directory.
//     *
//     * @return $this Current instance.
//     * @since 0.1.0
//     */
//    public function setTemporaryDirectory($temporaryDirectory)
//    {
//        $this->temporaryDirectory = $temporaryDirectory;
//        return $this;
//    }
}
