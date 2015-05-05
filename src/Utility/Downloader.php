<?php

namespace Etki\Testing\AllureFramework\Runner\Utility;

use Guzzle\Http\Client;

/**
 * Downloads stuff.
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
     * Filesystem helper instance.
     *
     * @type Filesystem
     * @since 0.10
     */
    private $filesystem;
    /**
     * Initializer.
     *
     * @param Client     $guzzle     Guzzle client.
     * @param Filesystem $filesystem Filesystem helper.
     *
     * @since 0.1.0
     */
    public function __construct(Client $guzzle, Filesystem $filesystem)
    {
        $this->guzzle = $guzzle;
        $this->filesystem = $filesystem;
    }

    /**
     * Downloads single file
     *
     * @param string $source Source file URL.
     * @param string $target Path to file to write.
     *
     * @return void
     * @since 0.1.0
     */
    public function download($source, $target)
    {
        $request = $this->guzzle->get($source);
        $response = $request->send();
        $this->filesystem->writeFile($target, $response->getBody(true));
    }
}
