<?php

namespace Doublemcz\NetteDoctrineOdm;

use Nette;

class Logger extends Nette\Object
{

	private $queries = [];

	/**
	 * @param array $array
	 */
	public function log($queryArray)
	{
		$this->queries[] = $queryArray;
	}

}
