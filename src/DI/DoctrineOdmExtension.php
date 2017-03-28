<?php

namespace Doublemcz\NetteDoctrineOdm\DI;

use Nette;
use Doublemcz;

class DoctrineOdmExtension extends Nette\DI\CompilerExtension
{

	/**
	 * @var array
	 */
	public $defaults = [
		'proxyDir' => '%tempDir%/proxies',
		'proxyNamespace' => 'Proxies',
		'hydratorDir' => '%tempDir%/hydrators',
		'hydratorNamespace' => 'Hydrators',
		'documentsDir' => '%appDir%/model/Documents',
		'loggerClass' => 'Doublemcz\NetteDoctrineOdm\Logger'
	];

	/**
	 * @inheritdoc
	 */
	public function loadConfiguration()
	{
		$config = $this->getConfig();
		$builder = $this->getContainerBuilder();

		$builder->addDefinition($this->prefix('annotationDriver'))
			->setFactory(
				'Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver::create',
				[$config['documentsDir']]
			);

		$builder->addDefinition($this->prefix('logger'))
			->setClass($config['loggerClass']);

		$builder->addDefinition($this->prefix('connection'))
			->setClass('Doctrine\MongoDB\Connection');

		$configuration = $builder->addDefinition($this->prefix('configuration'))
			->setClass('Doctrine\ODM\MongoDB\Configuration')
			->addSetup('setProxyDir', [$config['proxyDir']])
			->addSetup('setHydratorDir', [$config['hydratorDir']])
			->addSetup('setProxyNamespace', [$config['proxyNamespace']])
			->addSetup('setHydratorNamespace', [$config['hydratorNamespace']])
			->addSetup('setMetadataDriverImpl', ['@' . $this->prefix('annotationDriver')])
			->addSetup('setLoggerCallable', ['@' . $this->prefix('logger') . '::log']);

		if (array_key_exists('database', $config)) {
			$configuration->addSetup('setDefaultDB', [$config['database']]);
		}

		$builder->addDefinition($this->prefix('documentManager'))
			->setFactory(
				'Doctrine\ODM\MongoDB\DocumentManager::create',
				['@' . $this->prefix('connection'), '@' . $this->prefix('configuration')]
			);
	}

	/**
	 * @param Nette\PhpGenerator\ClassType $class
	 */
	public function afterCompile(Nette\PhpGenerator\ClassType $class)
	{
		$initialize = $class->methods['initialize'];
		$initialize->addBody('Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver::registerAnnotationClasses();');
	}

	/**
	 * @return array
	 */
	public function getConfig()
	{
		return parent::getConfig($this->defaults);
	}

}
