<?php

namespace Etki\Testing\AllureFramework\Runner\Utility;

use Rhumsaa\Uuid\Uuid;

/**
 * Basic wrapper around rhumsa/uuid to keep things in container.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Utility
 * @author  Etki <etki@etki.name>
 */
class UuidFactory
{
    /**
     * Creates random UUID.
     *
     * @codeCoverageIgnore
     *
     * @return Uuid
     * @since 0.1.0
     */
    public function uuid4()
    {
        return Uuid::uuid4();
    }
}
