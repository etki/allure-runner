<?php

namespace Etki\Testing\AllureFramework\Runner\Utility;

use Guzzle\Http\Client;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Downloads stuff.
 *
 * Symfony filesystem isn't used because of atomic renaming support that can't
 * be tested using VFS. There is no need in atomic writes in this project.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Testing\AllureFramework\Runner\Utility
 * @author  Etki <etki@etki.name>
 */
class Downloader
{
    /**
     * Guzzle instance.
     *
     * @type Client
     * @since 0.1.0
     */
    private $guzzle;
    /**
     * Initializer.
     *
     * @since 0.1.0
     */
    public function __construct()
    {
        $this->guzzle = new Client;
    }

    /**
     * Adds plugin to guzzle.
     *
     * @param EventSubscriberInterface $plugin Plugin to add.
     *
     * @return void
     * @since 0.1.0
     */
    public function addGuzzlePlugin(EventSubscriberInterface $plugin)
    {
        $this->guzzle->addSubscriber($plugin);
    }

    /**
     * Downloads single file
     *
     * @param string $source Source file URL
     * @param string $target Target file on filesystem.
     *
     * @return void
     * @since 0.1.0
     */
    public function download($source, $target)
    {
        $request = $this->guzzle->get($source);
        $response = $request->send();
        file_put_contents($target, $response->getBody());
    }
}
