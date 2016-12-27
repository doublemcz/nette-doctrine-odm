<?php

namespace Doublemcz\NetteDoctrineOdm\DI;

use Nette;
use Doublemcz;

class OdmExtension extends Nette\DI\CompilerExtension
{
	/**
	 * @var array
	 */
	public $defaults = [
		'proxyDir' => '%tempDir%/proxies',
		'proxyNamespace' => 'Proxies',
		'hydratorNamespace' => 'Hydrators',
		'documentsDir' => '%appDir%/model/Documents'
	];


	/**
	 * @inheritdoc
	 */
	public function loadConfiguration()
	{
//		$this->name = 'doctrineOdm';
		$config = $this->getConfig();
		$builder = $this->getContainerBuilder();

		//AnnotationDriver::registerAnnotationClasses();

		$builder->addDefinition($this->prefix('annotationDriver'))
			->setClass('Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver::create', [$config['documentsDir']]);


		$builder->addDefinition($this->prefix('connection'))
			->setClass('Doctrine\MongoDB\Connection');

		$configuration = $builder->addDefinition($this->prefix('configuration'))
			->setClass('Doctrine\ODM\MongoDB\Configuration')
			->addSetup('setProxyDir', [$config['proxyDir']])
			->addSetup('setProxyNamespace', [$config['proxyNamespace']])
			->addSetup('setHydratorNamespace', [$config['hydratorNamespace']])
			->addSetup('setMetadataDriverImpl', ['@' . $this->prefix('anotationDriver')]);

		if (array_key_exists('database', $config)) {
			$configuration->addSetup('setDefaultDB', [$config['database']]);
		}

		$builder->addDefinition($this->prefix('documentManager'))
			->setClass('DocumentManager::create', ['@' . $this->prefix('connection'), '@' . $this->prefix('configuration')]);
	}

	/**
	 * @return array
	 */
	public function getConfig()
	{
		return parent::getConfig($this->defaults);
	}

	/**
	 * @param \Nette\Configurator $configurator
	 */
	public static function register(Nette\Configurator $configurator)
	{
		$configurator->onCompile[] = function ($config, Nette\DI\Compiler $compiler) {
			$compiler->addExtension('doctrineOdm', new OdmExtension());
		};
	}
}
