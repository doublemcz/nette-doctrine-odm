<?php

use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';

$configurator = new Nette\Configurator;

$configurator->enableTracy(__DIR__ . '/../log');
$configurator->setTempDirectory(__DIR__ . '/../temp');
$configurator->addConfig(__DIR__ . '/../config.neon');

$container = $configurator->createContainer();