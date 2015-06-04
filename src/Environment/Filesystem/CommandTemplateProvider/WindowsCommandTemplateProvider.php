<?php

namespace Etki\Testing\AllureFramework\Runner\Environment\Filesystem\CommandTemplateProvider;

use Etki\Testing\AllureFramework\Runner\Environment\Filesystem\ExecutableLocationCommandProviderInterface;
use Etki\Testing\AllureFramework\Runner\Environment\Filesystem\FileLocationCommandProviderInterface;

/**
 * Provides command templates for Windows OS.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Environment\Filesystem\CommandTemplateProvider
 * @author  Etki <etki@etki.name>
 */
class WindowsCommandTemplateProvider implements
    ExecutableLocationCommandProviderInterface,
    FileLocationCommandProviderInterface
{
    /**
     * Template for executable search command.
     *
     * @since 0.1.0
     */
    const COMMAND_TEMPLATE_WHERE = 'where %s';

    /**
     * {@inheritdoc}
     *
     * @codeCoverageIgnore
     *
     * @return string[]
     * @since 0.1.0
     */
    public function getExecutableLocationCommandTemplates()
    {
        return array(self::COMMAND_TEMPLATE_WHERE);
    }

    /**
     * {@inheritdoc}
     *
     * @codeCoverageIgnore
     *
     * @return string[]
     * @since 0.1.0
     */
    public function getFileLocationCommandTemplates()
    {
        return array();
    }
}
