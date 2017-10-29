<?php
/**
 * Created by PhpStorm.
 * User: chengyang
 * Date: 2017/10/29
 * Time: 下午8:33
 */

namespace App\Http\Controllers\Api;

use App\Tag;
use Illuminate\Http\Request;
use App\Http\Requests\TagRequest;
use App\Repositories\TagRepository;

class TagController extends ApiController
{
    protected $tag;

    public function __construct(TagRepository $tag)
    {
        parent::__construct();

        $this->tag = $tag;
    }

    public function index()
    {
        return $this->response->collection($this->tag->page());
    }

    public function getList()
    {
        return $this->response->collection($this->tag->all());
    }

    public function store(TagRequest $request)
    {
        $this->tag->store($request->all());

        return $this->response->withNoContent();
    }

    public function edit($id)
    {
        return $this->response->item($this->tag->getById($id));
    }

    public function update(Request $request, $id)
    {
        $this->tag->update($id, $request->except('tag'));

        return $this->response->withNoContent();
    }

    public function destory($id)
    {
        $this->tag->destory($id);

        return $this->response->withNoContent();
    }
}