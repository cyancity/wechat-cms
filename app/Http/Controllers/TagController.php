<?php

namespace App\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Repositories\TagRepository;

class TagController extends Controller
{
	protected $tag;

    public function __construct(TagRepository $tag)
    {
        $this->tag = $tag;
    }

    public function index()
    {
    	$tags = $this->tag->all();

    	return view('tag.index', compact('tags'));
    }

    public function show($tag)
    {
    	$tag = $this->tag->getByName($tag);

    	if (!tag) {
    		abort(404);
    	}

    	$articles = $tag->articles;
    	$discussions = $tag->discussions;

    	return view('tag.show', compact('tag', 'articles', 'discussions'))
    }
}

