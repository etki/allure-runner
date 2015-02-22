<?php

namespace Etki\Testing\AllureFramework\Runner\Configuration;

use Etki\Testing\AllureFramework\Runner\IO\PrefixAwareIOControllerInterface;

/**
 * Allure runner configuration.
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
    private $outputDirectory;
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
     * @type int
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
     * @return string[] List of paths to source directories.
     * @since 0.1.0
     */
    public function getSources()
    {
        return array_values($this->sources);
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
     * Returns output directory.
     *
     * @return string
     * @since 0.1.0
     */
    public function getOutputDirectory()
    {
        return $this->outputDirectory;
    }

    /**
     * Sets output directory.
     *
     * @param string $outputDirectory Output directory.
     *
     * @return $this Current instance.
     * @since 0.1.0
     */
    public function setOutputDirectory($outputDirectory)
    {
        $this->outputDirectory = $outputDirectory;
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
     * @return int
     * @since 0.1.0
     */
    public function getVerbosity()
    {
        return $this->verbosity;
    }

    /**
     * Sets verbosity.
     *
     * @param int $verbosity Verbosity.
     *
     * @return $this Current instance.
     * @since
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
}
