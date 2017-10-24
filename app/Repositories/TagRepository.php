<?php

namespace App\Repositories;


use App\Tag;

class TagRepository
{
	use IndexRepository;
	
	protected $model;

    public function __construct(Tag $tag)
    {
    	$this->model = $tag;	    
    }

    public function getByName($name)
    {
    	return $this->model->where('tag', $name)->first();
    }
}