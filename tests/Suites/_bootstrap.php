<?php

use Doctrine\Common\Annotations\AnnotationReader;
use Etki\Testing\AllureFramework\Runner\Tests\Support\Data\Loader\ZipArchiveLoader;
use Codeception\Util\Fixtures;

Fixtures::add('service.testing.archive_loader', new ZipArchiveLoader);

AnnotationReader::addGlobalIgnoredName('type');
AnnotationReader::addGlobalIgnoredName('test');
AnnotationReader::addGlobalIgnoredName('expectedException');
AnnotationReader::addGlobalIgnoredName('codingStandardsIgnoreStart');
AnnotationReader::addGlobalIgnoredName('codingStandardsIgnoreEnd');

error_reporting(E_ALL ^ E_STRICT);
