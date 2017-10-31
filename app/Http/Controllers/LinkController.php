<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Repositories\LinkRepositories;


class LinkController extends Controller
{
	protected $link

    public function __construct(LinkRepository $link)
    {
        $this->link = $link;
    }

    public function index()
    {
    	$links = $this->link->page();

    	return view('link.index', compact('links'));
    }
}
