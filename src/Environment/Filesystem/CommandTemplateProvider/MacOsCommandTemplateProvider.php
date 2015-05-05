<?php

namespace Etki\Testing\AllureFramework\Runner\Environment\Filesystem\CommandTemplateProvider;

/**
 * This class provides command templates for Mac OS.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Environment\Filesystem\CommandTemplateProvider
 * @author  Etki <etki@etki.name>
 */
class MacOsCommandTemplateProvider extends UnixCommandTemplateProvider
{
    /**
     * Command template for locating single file.
     *
     * @since 0.1.0
     */
    const COMMAND_TEMPLATE_MDFIND = 'mdfind %s';

    /**
     * {@inheritdoc}
     *
     * @return string[]
     * @since 0.1.0
     */
    public function getFileLocationCommandTemplates()
    {
        $templates = array_merge(
            parent::getFileLocationCommandTemplates(),
            array(self::COMMAND_TEMPLATE_MDFIND,)
        );
        return $templates;
    }
}
