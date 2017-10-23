<?php

namespace App\Http\Controllers;

use App\Category;

/**
* 
*/
class CategoryRepository
{
	
	use IndexRepository;

	protected $model;

	function __construct(Category $categoru)
	{
		$this->model = $category;
	}

	public function getByName($name)
	{
		return $this->model->where('name', $name)->first();
	}
}