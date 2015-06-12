<?php

namespace Etki\Testing\AllureFramework\Runner\Tests\Support\Reflection;

/**
 * This registry holds FQCNs required by tests.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Tests\Support\Reflection
 * @author  Etki <etki@etki.name>
 */
class Registry
{
    /**
     * Main runner FQCN.
     *
     * @since 0.1.0
     */
    const RUNNER_CLASS = 'Etki\Testing\AllureFramework\Runner\Runner';
    
    /**
     * PHP filesystem API FQCN.
     *
     * @since 0.1.0
     */
    const PHP_FILESYSTEM_API_CLASS
        = 'Etki\Testing\AllureFramework\Runner\Utility\PhpApi\Filesystem';
    
    /**
     * Symfony process FQCN.
     *
     * @since 0.1.0
     */
    const SYMFONY_PROCESS_CLASS = 'Symfony\Component\Process\Process';
    
    /**
     * Symfony filesystem FQCN.
     *
     * @since 0.1.0
     */
    const SYMFONY_FILESYSTEM_CLASS = 'Symfony\Component\Filesystem\Filesystem';
    
    /**
     * Process factory FQCN.
     *
     * @since 0.1.0
     */
    const PROCESS_FACTORY_CLASS
        = 'Etki\Testing\AllureFramework\Runner\Environment\ProcessFactory';
    
    /**
     * Run factory FQCN.
     *
     * @since 0.1.0
     */
    const ALLURE_CLI_RUN_FACTORY_CLASS
        = 'Etki\Testing\AllureFramework\Runner\AllureCli\RunFactory';
    
    /**
     * Run factory FQCN.
     *
     * @since 0.1.0
     */
    const ALLURE_CLI_RUN_CLASS
        = 'Etki\Testing\AllureFramework\Runner\AllureCli\Run';
    
    /**
     * Allure CLI runner FQCN.
     *
     * @since 0.1.0
     */
    const ALLURE_CLI_RUNNER_CLASS
        = 'Etki\Testing\AllureFramework\Runner\AllureCli\Runner';
    
    /**
     * Allure CLI result output parser FQCN.
     *
     * @since 0.1.0
     */
    const ALLURE_CLI_RESULT_OUTPUT_PARSER_CLASS
        = 'Etki\Testing\AllureFramework\Runner\AllureCli\ResultOutputParser';
    
    /**
     * Command builder FQCN.
     *
     * @since 0.1.0
     */
    const ALLURE_CLI_COMMAND_BUILDER_CLASS
        = 'Etki\Testing\AllureFramework\Runner\AllureCli\CommandBuilder';
    
    /**
     * Command builder factory FQCN.
     *
     * @since 0.1.0
     */
    const ALLURE_CLI_COMMAND_BUILDER_FACTORY_CLASS
        = 'Etki\Testing\AllureFramework\Runner\AllureCli\CommandBuilderFactory';
    
    /**
     * Allure cli run options DTO FQCN.
     *
     * @since 0.1.0
     */
    const ALLURE_CLI_RUN_OPTIONS_CLASS
        = 'Etki\Testing\AllureFramework\Runner\AllureCli\RunOptions';
    
    /**
     * File locator FQCN.
     *
     * @since 0.1.0
     */
    const FILE_LOCATOR_CLASS
        = 'Etki\Testing\AllureFramework\Runner\Environment\Filesystem\FileLocator';
    
    /**
     * I/O controller FQIN.
     *
     * @since 0.1.0
     */
    const IO_CONTROLLER_INTERFACE
        = 'Etki\Testing\AllureFramework\Runner\IO\IOControllerInterface';
    
    /**
     * Configuration builder FQCN.
     *
     * @since 0.1.0
     */
    const CONFIGURATION_BUILDER_CLASS
        = 'Etki\Testing\AllureFramework\Runner\Configuration\Builder';

    /**
     * Configuration validator FQCN.
     *
     * @since 0.1.0
     */
    const CONFIGURATION_VALIDATOR_CLASS
        = 'Etki\Testing\AllureFramework\Runner\Configuration\Validator';
    
    /**
     * Configuration FQCN.
     *
     * @since 0.1.0
     */
    const CONFIGURATION_CLASS
        = 'Etki\Testing\AllureFramework\Runner\Configuration\Configuration';
    
    /**
     * Filesystem helper FQCN.
     *
     * @since 0.1.0
     */
    const FILESYSTEM_HELPER_CLASS
        = 'Etki\Testing\AllureFramework\Runner\Utility\Filesystem';
    
    /**
     * Path resolver FQCN.
     *
     * @since 0.1.0
     */
    const PATH_RESOLVER_CLASS
        = 'Etki\Testing\AllureFramework\Runner\Utility\Filesystem\PathResolver';

    /**
     * Extractor FQCN.
     *
     * @since 0.1.0
     */
    const EXTRACTOR_CLASS
        = 'Etki\Testing\AllureFramework\Runner\Utility\Extractor';

    /**
     * Downloader FQCN.
     *
     * @since 0.1.0
     */
    const DOWNLOADER_CLASS
        = 'Etki\Testing\AllureFramework\Runner\Utility\Downloader';
    
    /**
     * Allure executable computer FQCN.
     *
     * @since 0.1.0
     */
    const ALLURE_EXECUTABLE_RESOLVER_CLASS
        = 'Etki\Testing\AllureFramework\Runner\Run\Scenario\AllureExecutableResolver';

    /**
     * Java executable locator FQCN.
     *
     * @since 0.1.0
     */
    const JAVA_EXECUTABLE_LOCATOR_CLASS
        = 'Etki\Testing\AllureFramework\Runner\Run\Scenario\JavaExecutableLocator';

    /**
     * FQCN of resolver for asset containing `.jar` file.
     *
     * @since 0.1.0
     */
    const JAR_ASSET_URL_RESOLVER_CLASS
        = 'Etki\Testing\AllureFramework\Runner\Run\Scenario\JarAssetUrlResolver';

    /**
     * `.jar` file locator FQCN.
     *
     * @since 0.1.0
     */
    const JAR_LOCATOR_CLASS
        = 'Etki\Testing\AllureFramework\Runner\Run\Scenario\JarLocator';
    
    /**
     * `.jar` file resolver FQCN.
     *
     * @since 0.1.0
     */
    const JAR_RESOLVER_CLASS
        = 'Etki\Testing\AllureFramework\Runner\Run\Scenario\JarResolver';

    /**
     * `.jar` file downloader FQCN.
     *
     * @since 0.1.0
     */
    const JAR_DOWNLOADER_CLASS
        = 'Etki\Testing\AllureFramework\Runner\Run\Scenario\JarDownloader';

    /**
     * Release asset resolver FQCN.
     *
     * @since 0.1.0
     */
    const RELEASE_ASSET_RESOLVER_CLASS
        = 'Etki\Testing\AllureFramework\Runner\Api\Github\ReleaseAssetResolver';
    
    /**
     * Github Allure release resolver FQCN.
     *
     * @since 0.1.0
     */
    const RELEASE_RESOLVER_CLASS
        = 'Etki\Testing\AllureFramework\Runner\Api\Github\ReleaseResolver';

    /**
     * Github API client FQCN.
     *
     * @since 0.1.0
     */
    const GITHUB_API_CLIENT_CLASS = 'Github\Client';
    
    /**
     * Github API repository-specific client FQCN.
     *
     * @since 0.1.0
     */
    const GITHUB_API_REPOSITORY_CLIENT_CLASS = 'Github\Api\Repo';
    
    /**
     * Github API release-specific client FQCN.
     *
     * @since 0.1.0
     */
    const GITHUB_API_RELEASE_CLIENT_CLASS = 'Github\Api\Repository\Releases';

    /**
     * Guzzle client FQCN.
     *
     * @since 0.1.0
     */
    const GUZZLE_CLIENT_CLASS = 'Guzzle\Http\Client';
    
    /**
     * Allure executable not found exception class.
     *
     * @since 0.1.0
     */
    const ALLURE_EXECUTABLE_NOT_FOUND_EXCEPTION_CLASS
        = 'Etki\Testing\AllureFramework\Runner\Exception\Run\AllureExecutableNotFoundException';

    /**
     * Invalid configuration exception class.
     *
     * @since 0.1.0
     */
    const INVALID_CONFIGURATION_EXCEPTION_CLASS
        = 'Etki\Testing\AllureFramework\Runner\Exception\Configuration\InvalidConfigurationException';

    /**
     * Exception thrown on non-zero exit codes exit.
     *
     * @since 0.1.0
     */
    const NON_ZERO_EXIT_CODE_EXCEPTION
        = 'Etki\Testing\AllureFramework\Runner\Exception\AllureCli\NonZeroExitCodeException';
}
