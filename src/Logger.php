<?php

namespace Doublemcz\NetteDoctrineOdm;

use Nette;

class Logger
{

	use Nette\SmartObject;

	private $queries = [];

	/**
	 * @param array $array
	 */
	public function log($queryArray)
	{
		$this->queries[] = $queryArray;
	}

}
