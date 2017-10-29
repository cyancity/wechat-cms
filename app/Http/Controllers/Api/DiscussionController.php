<?php

namespace App\Http\Controller\Api;

use Illuminate\Http\Request;
use App\Http\Request\DiscussionRequest;
use App\Repository\DiscussionRepository;

class DiscussionController extends ApiController
{
  protected $discussion;

  public function __construct(DiscussionRepository $discussion)
  {
    parent::__construct();

    $this->discussion  = $discussion;
  }

  public function index()
  {
    return $this->response->collection($this->discussion->page(10, 'desc'));
  }

  public function store(DiscussionRequest $request)
  {
    $data = array_merge($request->all(), [
      'user_id' => \Auth::id(),
      'last_user_id' => \Auth::id()
    ]);

    $this->discussion->store($data);

    return $this->response->withNoContent();
  }

  public function status($id, Request $request)
  {
    $input = $request->all();

    $this->discussion->updateWithoutTags($id, $input);

    return $this->response->withNoContent();
  }

  public function edit($id)
  {
      return $this->response->item($this->discussion->getById($id));
  }

  public function update(DiscussionRequest $request, $id)
  {
    $data = array_merge($request->all(), [
      'last_user_id' => \Auth::id()
    ]);

    $this->discussion->update($id, $data);

    return $this->response->withNoContent();
  }

  public function destory($id)
  {
      $this->discussion->destory($id);

      return $this->response->withNoContent();
  }
}