<?php

namespace App\Repositories;

use App\Tools\IP;
use App\Visitor;

/**
 * summary
 */
class VisitorRepository
{

	use IndexRepository;
	

	protected $model;

	protected $ip;
    /**
     * summary
     */
    public function __construct(Visitor $visitor, IP $ip)
    {
        $this->model = $visitor->with('article');

        $this->ip = $ip;
    }

    public function log($article_id)
    {
    	$ip = $this->ip->get();

    	if ($this->hasArticleIp($article_id, $ip)) {
    		$this->model->where('article_id', $article_id)
    					->where('ip', $ip)
    					->increment('clicks');	
    	} else {
    		$data = [
    			'ip' => $ip,
    			'article_id' => $article_id,
    			'clicks' => 1
    		];
    		$this->model->firstOrFail($data);
    	}
    }

    public function hasArticleIp($article_id, $ip)
    {
    	return $this->model
    	 			->where('article_id', $article_id)
    	 			->where('ip', $ip)
    	 			->count() ? true : false;
    }

    public function getAllClicks()
    {
    	return $this->model->sum('clicks');
    }
}