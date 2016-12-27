<?php

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

file_put_contents('D:\\test.txt', 'test');
$configurator = new Nette\Configurator;

$configurator->setTempDirectory(TEMP_DIR);
$configurator->addConfig(__DIR__ . '/../config.neon');
//
$container = $configurator->createContainer();
//var_dump($container);

echo 'test';
