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

	public function page($number = 10, $sort = 'desc', $sortColumn = 'created_at')
	{
		$this->model = $this->checkAuthScope();
		return $this->model->orderBy($sortColumn, $sort)->paginate($number);
	}

	public function getById($id)
	{
		return $this->model->withoutGlobalScope(StatusScope::class)->findOrFail($id);
	}

	public function update($id, $input)
	{
		$this->model = $this->model->withoutGlobalScope(StatusScope::class)->findOrFail($id)

		return $this->save($this->model, $input);
	}

	public function checkAuthScope()
	{
		if (auth()->check() && auth()->user()->is_admin) {
			$this->model = $this->model->withoutGlobalScope(StatusScope::class);
		}

		return $this->model;
	}

	// public function destory($id)
	// {
	// 	return $this->getById($id)->delete();
	// }
}