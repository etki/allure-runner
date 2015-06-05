<?php

namespace Etki\Testing\AllureFramework\Runner\Configuration;

/**
 * Configuration schema.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Configuration
 * @author  Etki <etki@etki.name>
 */
class Schema
{
    /**
     * Enabled parameter key.
     *
     * @since 0.1.0
     */
    const PARAMETER_ENABLED = 'enabled';
    /**
     * Sources parameter key.
     *
     * @since 0.1.0
     */
    const PARAMETER_SOURCES = 'sources';
    /**
     * Report path parameter key.
     *
     * @since 0.1.0
     */
    const PARAMETER_REPORT_PATH = 'reportPath';
    /**
     * Report version parameter key.
     *
     * @since 0.1.0
     */
    const PARAMETER_REPORT_VERSION = 'reportVersion';
    /**
     * Path to executable parameter key.
     *
     * @since 0.1.0
     */
    const PARAMETER_EXECUTABLE = 'executable';
    /**
     * Path to .jar location parameter key.
     *
     * @since 0.1.0
     */
    const PARAMETER_JAR = 'jar';
    /**
     * Verbosity level key.
     *
     * @since 0.1.0
     */
    const PARAMETER_VERBOSITY = 'verbosity';
    /**
     * Output prefix format key.
     *
     * @since 0.1.0
     */
    const PARAMETER_OUTPUT_PREFIX_FORMAT = 'outputPrefixFormat';
    /**
     * Key for 'should i download missing jar' parameter.
     *
     * @since 0.1.0
     */
    const PARAMETER_DOWNLOAD_MISSING_JAR = 'downloadMissingJar';
    /**
     * Preferred allure version parameter key.
     *
     * @since 0.1.0
     */
    const PARAMETER_PREFERRED_ALLURE_VERSION = 'preferredAllureVersion';
    /**
     * 'Throw exception on missing executable' parameter key.
     *
     * @since 0.1.0
     */
    const PARAMETER_THROW_ON_MISSING_EXECUTABLE = 'throwOnMissingExecutable';
    /**
     * 'Throw exception on invalid configuration' parameter key.
     *
     * @since 0.1.0
     */
    const PARAMETER_THROW_ON_INVALID_CONFIGURATION
        = 'throwOnInvalidConfiguration';
    /**
     * 'Throw exception on non-zero exit code' parameter key.
     *
     * @since 0.1.0
     */
    const PARAMETER_THROW_ON_NON_ZERO_EXIT_CODE = 'throwOnInvalidConfiguration';
    /**
     * Key for 'should i clean generated files' parameter.
     *
     * @since 0.1.0
     */
    const PARAMETER_CLEAN_GENERATED_FILES = 'cleanGeneratedFiles';
    /**
     * Temporary directory parameter key.
     *
     * @since 0.1.0
     */
    const PARAMETER_TEMPORARY_DIRECTORY = 'temporaryDirectory';
    /**
     * Use VFS parameter key.
     *
     * @since 0.1.0
     */
    const PARAMETER_USE_VFS = 'useVfs';
    /**
     * Dry run parameter key.
     *
     * @since 0.1.0
     */
    const PARAMETER_DRY_RUN = 'dryRun';

    /**
     * List of configuration parameters.
     *
     * @codeCoverageIgnore
     *
     * @return string[]
     * @since 0.1.0
     */
    public static function getParameterList()
    {
        $list = array(
            self::PARAMETER_ENABLED,
            self::PARAMETER_SOURCES,
            self::PARAMETER_REPORT_VERSION,
            self::PARAMETER_REPORT_PATH,
            self::PARAMETER_EXECUTABLE,
            self::PARAMETER_JAR,
            self::PARAMETER_VERBOSITY,
            self::PARAMETER_OUTPUT_PREFIX_FORMAT,
            self::PARAMETER_DOWNLOAD_MISSING_JAR,
            self::PARAMETER_PREFERRED_ALLURE_VERSION,
            self::PARAMETER_THROW_ON_MISSING_EXECUTABLE,
            self::PARAMETER_THROW_ON_INVALID_CONFIGURATION,
            self::PARAMETER_THROW_ON_NON_ZERO_EXIT_CODE,
            self::PARAMETER_CLEAN_GENERATED_FILES,
            self::PARAMETER_TEMPORARY_DIRECTORY,
            self::PARAMETER_USE_VFS,
            self::PARAMETER_DRY_RUN,
        );
        return $list;
    }
}
