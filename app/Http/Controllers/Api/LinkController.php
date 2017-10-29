<?php
/**
 * Created by PhpStorm.
 * User: chengyang
 * Date: 2017/10/29
 * Time: 下午8:01
 */

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests\LinkRequest;
use App\Repositories\LinkRepository;

class LinkController extends ApiController
{
    protected $link;
    protected $manager;

    public function __construct(LinkRepository $link)
    {
        parent::__construct();

        $this->link = $link;

        $this->manager = app('uploader');
    }

    public function index()
    {
        return $this->response->collection($this->link->page());
    }

    public function store(LinkRequest $request)
    {
        $data = $request->all();

        $data['status'] = isset($data['status']);

        $this->link->store($data);

        return $this->response->withNoContent();
    }

    public function status($id, Request $request)
    {
        $input = $request->all();

        $this->link->update($id, $input);

        return $this->response->withNoContent();
    }

    public function edit($id)
    {
        return $this->response->item($this->link->getById($id));
    }

    public function update(LinkRequest $request, $id)
    {
        $this->link->update($id, $request->all());

        return $this->response->withNoContent();
    }

    public function destory($id)
    {
        $this->link->destory($id);

        return $this->response->withNoContent();
    }
}