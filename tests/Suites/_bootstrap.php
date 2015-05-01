<?php

use Etki\Testing\AllureFramework\Runner\Tests\Support\Data\Loader\ZipArchiveLoader;
use Codeception\Util\Fixtures;

Fixtures::add('service.testing.archive_loader', new ZipArchiveLoader);
// This is global bootstrap for autoloading
