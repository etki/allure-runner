<?php

namespace Etki\Testing\AllureFramework\Runner\Environment\Filesystem;

/**
 * Realizations of this class should provide list of command templates that
 * locate single file by it's name.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Environment\Filesystem
 * @author  Etki <etki@etki.name>
 */
interface FileLocationCommandsProviderInterface
{
    /**
     * Returns list of sprintf-ready regular file location command templates.
     *
     * @return string[]
     * @since 0.1.0
     */
    public function getFileLocationCommandTemplates();
}
