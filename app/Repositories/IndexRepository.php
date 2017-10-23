<?php

namespace App\Repositories;

trait IndexRepository
{
	public function getNumber()
	{
		return $this->model->count();
	}

	public function updateColumn($id, $input)
	{
		$this->model = $this->getById($id);

		foreach ($input as $key => $value) {
			$this->model->{$key} = $value;
		}

		return $this->model->save();
	}

	public function getById($id)
	{
		return $this->model->findOrFail($id);
	}

	public function all()
	{
		return $this->model->get();
	}

	public function page($number = 10, $sort = 'desc', $sortColumn = 'created_at')
	{
		return $this->model->ordetBy($sortColumn, $sort)->paginate($number);
	}

	public function store($input)
	{
		return $this->save($this->model, $input);
	}

	public function update($id, $input)
	{
		$this->model = $this->getById($id);
		return $this->save($this->model, $input);
	}

	public function save($model, $input)
	{
		$model->fill($input);
		$model->save();
		return $model;
	}
}