<?php

namespace App\Repositories;

use App\Discussion;
use App\Scopes\StatusScope;

/**
 * summary
 */
class DiscussionRepository
{
	use IndexRepository;
	
	protected $model;

    /**
     * summary
     */
    public function __construct(Discussion $discussion)
    {
        $this->model = $discussion;
    }

    public function page($number = 10, $sort = 'asc', $sortColumn = 'created_at')
    {
    	$this->model = $this->checkAuthScope();

    	return $this->model->orderBy($sortColumn, $sort)->paginate($number);
    }

    public function getById($id)
    {
    	$this->model = $this->checkAuthScope();

    	return $this->model->findOrFail($id);
	}

	public function store($data)
	{
		$discussion = $this->model->create($data);

		if (is_array($data['tags'])) {
			$this->syncTag($discussion, $data['tags']);
		} else {
			$this->syncTag($discussion, json_decode($data['tags']));
		}

		return $discussion;
	}

	public function update(int $id, array $data)
	{
		$this->model = $this->checkAuthScope();

		$discussion = $this->model->findOrFail($id);

		if (is_array($data['tags'])) {
			$this->syncTag($discussion, $data['tags']);
		} else {
			$this->syncTag($discussion, json_decode($data['tags']));
		}

		return $discussion->update($data);
	}

	public function updateWithoutTags(int $id, array $data)
	{
		$this->model = $this->checkAuthScope();

		$discussion = $this->model->findOrFail($id);

		return $discussion->update($data);
	}

	public function checkAuthScope()
	{
		if (auth()->check() && auth()->user()->is_admin) {
			$this->model = $this->model->withoutGlobalScope(StatusScope::class);
		}

		return $this->model;
	}
}

