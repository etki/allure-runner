<?php

namespace Etki\Testing\AllureFramework\Runner\Adapter\Codeception\Configuration;

use Etki\Testing\AllureFramework\Runner\Configuration\Configuration;
use Codeception\Configuration as CodeceptionConfiguration;
use Etki\Testing\AllureFramework\Runner\Configuration\Verbosity;
use Etki\Testing\AllureFramework\Runner\Exception\Configuration\BadConfigurationException;
use Etki\Testing\AllureFramework\Runner\Utility\Filesystem as FilesystemUtility;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Builds configuration from Codeception module configuration.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Adapter\Codeception
 * @author  Etki <etki@etki.name>
 */
class Builder
{
    /**
     * Builds new runner configuration using codeception extension settings.
     * 
     * Glorious war against 80-symbol limit!
     *
     * @param array $extensionConfiguration Extension configuration.
     *
     * @SuppressWarnings(PHPMD.LongVariableName)
     *
     * @return Configuration
     * @since 0.1.0
     */
    public function build(array $extensionConfiguration)
    {
        $configuration = new Configuration;
        $this->validateConfiguration($extensionConfiguration);
        foreach (Schema::getParameterNames() as $parameter) {
            if (!isset($extensionConfiguration[$parameter])) {
                $extensionConfiguration[$parameter]
                    = Schema::getDefaultValue($parameter);
            }
        }
        $verbosity = $extensionConfiguration[Schema::PARAMETER_NAME_VERBOSITY];
        if ($verbosity === Schema::PARAMETER_VALUE_AUTO) {
            $extensionConfiguration[Schema::PARAMETER_NAME_VERBOSITY]
                = $this->calculateVerbosity();
        }
        $reportVersionParameter = Schema::PARAMETER_NAME_REPORT_VERSION;
        $reportVersion = $extensionConfiguration[$reportVersionParameter];
        if ($reportVersion === Schema::PARAMETER_VALUE_AUTO) {
            $extensionConfiguration[$reportVersionParameter]
                = $this->calculateReportVersion();
        }
        $sources = $extensionConfiguration[Schema::PARAMETER_NAME_SOURCES];
        $configuration->addSources($this->convertSources($sources));
        $configuration->setVerbosity(
            $extensionConfiguration[Schema::PARAMETER_NAME_VERBOSITY]
        );
        $configuration->setExecutable(
            $extensionConfiguration[Schema::PARAMETER_NAME_EXECUTABLE]
        );
        $configuration->setJar(
            $extensionConfiguration[Schema::PARAMETER_NAME_JAR]
        );
        $outputDirectory = $this->calculateOutputDirectory(
            $extensionConfiguration[Schema::PARAMETER_NAME_OUTPUT_DIRECTORY]
        );
        $configuration->setReportPath($outputDirectory);
        $configuration->setReportVersion(
            $extensionConfiguration[Schema::PARAMETER_NAME_REPORT_VERSION]
        );
        
        $outputPrefixFormatParameter
            = Schema::PARAMETER_NAME_OUTPUT_PREFIX_FORMAT;
        $configuration->setOutputPrefixFormat(
            $extensionConfiguration[$outputPrefixFormatParameter]
        );
        return $configuration;
    }

    /**
     * Converts sources into absolute paths.
     *
     * @param string|string[] $sources
     *
     * @return string[] List of absolute paths to data sources.
     * @since 0.1.0
     */
    private function convertSources($sources)
    {
        if (is_string($sources)) {
            $sources = array($sources,);
        }
        $filesystem = new Filesystem;
        $filesystemUtility = new FilesystemUtility;
        foreach ($sources as &$source) {
            $prefix = null;
            if (!$filesystem->isAbsolutePath($source)) {
                $prefix = rtrim(CodeceptionConfiguration::outputDir(), '\\/');
            }
            $source = $filesystemUtility->normalizePath($source, $prefix);
        }
        return $sources;
    }

    /**
     * Validates configuration that came from Codeception.
     *
     * @param array $configuration Configuration to examine.
     *
     * @throws BadConfigurationException Thrown if any of parameters didn't pass
     *                                   validation.
     *
     * @return void
     * @since 0.1.0
     */
    private function validateConfiguration(array $configuration)
    {
        $invalidParameters = array();
        foreach (Schema::getParameterNames() as $parameterName) {
            if (!isset($configuration[$parameterName])) {
                continue;
            }
            $parameterValue = $configuration[$parameterName];
            if (!Schema::validateParameter($parameterName, $parameterValue)) {
                $invalidParameters[] = $parameterName;
            }
        }
        if ($invalidParameters) {
            $message = sprintf(
                'Following provided parameters were invalid: %s. Please ' .
                're-check extensions section of your Codeception ' .
                'configuration.',
                implode(', ', $invalidParameters)
            );
            throw new BadConfigurationException($message);
        }
    }

    /**
     * Calculates default verbosity value.
     *
     * @return int
     * @since 0.1.0
     */
    private function calculateVerbosity()
    {
        return Verbosity::LEVEL_INFO;
    }

    /**
     * Calculates report version to use.
     *
     * @return string
     * @since 0.1.0
     */
    private function calculateReportVersion()
    {
        return Configuration::DEFAULT_REPORT_VERSION;
    }

    /**
     * Calculates output directory. While it may be either relative or absolute,
     * configuration must receive an absolute one.
     *
     * @param string $directory Specified directory.
     *
     * @return string Calculated path.
     * @since 0.1.0
     */
    private function calculateOutputDirectory($directory)
    {
        $filesystem = new Filesystem;
        if ($filesystem->isAbsolutePath($directory)) {
            return $directory;
        }
        $outputDir = rtrim(CodeceptionConfiguration::outputDir(), '\\/');
        return $outputDir . DIRECTORY_SEPARATOR . $directory;
    }
}
