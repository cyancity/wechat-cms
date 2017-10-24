<?php

namespace App\Repository;

use App\Link;
use App\Scopes\StatusScope;

/**
* 
*/
class LinkRepository
{

	use IndexRepository;

	protected $model;
	
	function __construct(Link $link)
	{
		$this->model = $link;
	}
}