<?php

namespace Etki\Testing\AllureFramework\Runner\Configuration;

use Etki\Testing\AllureFramework\Runner\Utility\Filesystem\PathResolver;

/**
 * Creates configuration and populates with default values.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Configuration
 * @author  Etki <etki@etki.name>
 */
class ConfigurationBuilder
{
    // @todo
    public function build(PathResolver $pathResolver)
    {
        $configuration = new Configuration;
    }
    
    public function populate(Configuration $configuration, array $values)
    {
        
    }
}
