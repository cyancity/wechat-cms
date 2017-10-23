<?php

namespace App\Repositories;

use App\Article;
use App\Scopes\DraftScope;

/**
* 
*/
class ArticleRepository
{
	use IndexRepository;
	protected $model;
	protected $visitor;

	function __construct(Article $article, VisitorRepository $visitor)
	{
		$this->model = $article;
		$this->visitor = $visitor;
	}

	public function page($number = 10, $sort = 'desc', $soryColumn = 'created_at')
	{
		$this->model = $this->checkAuthScope();
		return $this->model->orderBy($sortColumn, $sort)->paginate($number);
	}

	public function getById($id)
	{
		return $this->model->withoutGlobalScope(DraftScope::class)->findOrFail($id);
	}

	public function update($id, $input)
	{
		$this->model =	$this->model->withoutGlobalScope(DraftScope::class)->findOrFail($id);
		return $this->save($this->model, $input);
	}

	public function getBySlug($slug)
	{
		$this->model = $this->checkAuthScope();
		$article = $this->model->where('slug', $slug)->firstOrFail();
		$article->increment('view_count');
		$this->visitor->log($article->id);
		return $article;
	}

	public function syncTag(array $tags
	{
		$this->model->tags()->sync($tags);
	}

	public function search($key)
	{
		$key = $trim($key);
		return $this->model
					->where('title', 'like', "%{$key}%")
					->orderBy('published_at', 'desc')
					->get();
	}

	public function destory($id)
	{
		return $this->getById($id)->delete();
	}
}