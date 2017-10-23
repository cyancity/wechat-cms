<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\ArticleRequest;
use App\Repository\ArticleRepository;

/**
* 
*/
class ArticleController extends ApiController
{
	protected $article;

	public function __construct(ArticleRepository $article)
	{
		parent::__construct();
		$this->article = $article;
	}

	public function index()
	{
		return $this->response->collection($this->article->page());
	}

	public function store(ArticleRequest $request)
	{
		$data = array_merge($request->all(), [
			'user_id' => \Auth::id(),
			'last_user_id' => \Auth::id()
		]);

		$data['is_draft'] = isset($data['is_draft']);
		$data['is_original'] = isset($data['is_original']);

		$this->article->store($data);
		$this->article->syncTag(json_decode($request->get('tags')));

		return $this->response->withNoContent();
	}
}