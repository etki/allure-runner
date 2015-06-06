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
    const CONFIGURATION_BUILDER_CLASS =
        'Etki\Testing\AllureFramework\Runner\Configuration\Builder';
    /**
     * Configuration FQCN.
     *
     * @since 0.1.0
     */
    const CONFIGURATION_CLASS =
        'Etki\Testing\AllureFramework\Runner\Configuration\Configuration';
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
}
