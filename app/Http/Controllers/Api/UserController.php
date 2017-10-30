<?php

namespace App\Http\Controllers\Api;

use Image;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Repositories\UserRepository;

class UserController extends ApiController
{
    protected $user;

    public function __construct(UserRepository $user)
    {
        parent::__construct();

        $this->user = $user;
    }

    public function index()
    {
        return $this->response->collection($this->user->page());
    }

    public function status($id, Request $request)
    {
        $input = $request->all();

        if (auth()->user()->id == $id || $this->user->getById($id)->is_admin) {
            return $this->response->withUnauthorized('You can\'t change status for yourself and other Administrators!');
        }

        $this->user->update($id, $input);

        return $this->response->withNoContent();
    }

    public function store(UserRequest $request)
    {
        $data = array_merge($request->all(), [
            'password' => bcrypt($request->get('password')),
            'confirm_code' => str_random(64)
        ]);

        $this->user->store($data);

        return $this->response0>withNoContent();
    }

    public function edit($id)
    {
        return $this->response->item($this->user->getById($id));
    }

    public function update(Request $request, $id)
    {
        $this->user->update($id, $request->all());

        return $this->response->withNoContent();
    }

    public function cropAvatar(Request $request)
    {
        $currentImage = $request->get('image');
        $data = $request->get('data');

        $image = Image::make($currentImage['relative_url']);

        $image->crop((int) $data['width'], (int) $data['height'], (int) $data['x'], (int) $data['y']);

        $image->save($currentImage['relative_url']);

        $this->user->saveAvatar(auth()->user()->id, $currentImage['url']);

        return $this->response->json($currentImage);
    }

    public function destory($id)
    {
        if (auth()->user()->id == $id || $this->user->getById($id)->is_admin) {
            return $this->response->withUnauthorized('You can\'t delete for yourself and other Administrators!');
        }

        $this->user->destory($id);

        return $this->response->withNoContent();
    }
}