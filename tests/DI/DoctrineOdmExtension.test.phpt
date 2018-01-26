<?php

require __DIR__ . '/../bootstrap.php';

$configurator = new Nette\Configurator;

$configurator->setTempDirectory(TEMP_DIR);
$configurator->addConfig(__DIR__ . '/../config.neon');
$container = $configurator->createContainer();

// This throws exception if the Class cannot be found and the test fails
$documentManager = $container->getByType(\Doctrine\ODM\MongoDB\DocumentManager::class);
\Tester\Assert::equal(get_class($documentManager), \Doctrine\ODM\MongoDB\DocumentManager::class);