<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Request\CategoryRequest;
use App\Repositories\CategoryRepository;

class CategoryController extends ApiController
{
    protected $category;

    public function __construct(CategoryRepository $category)
    {
        parent::__construct();

        $this->category = $category;
    }

    public function index()
    {
        return $this->response->collection($this->category->page());
    }

    public function getList()
    {
        return $this->response->collection($this->category->all());
    }

    public function store(CategoryRequest $request)
    {
        $this->category->store($request->all());

        return $this->response->withNoContent();
    }

    public function status($id, Request $request)
    {
        $input = $request->all();

        $this->category->updateColumn($id, $input);

        return $this->response->withNoContent();
    }

    public function edit($id)
    {
        return $this->response->item($this->category->getById($id));
    }

    public function update(CategoryRequest $request, $id)
    {
        $this->category->update($id, $request->all());

        return $this->response->withNoContent();
    }

    public function destory($id)
    {
        $this->category->destory($id);

        return $this->response->withNoContent();
    }
}