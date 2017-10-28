<?php

namespace App\Http\Controllers\Api;

use Auth;
use Illuminate\Http\CommentRequest;
use App\Repositories\CommentRepositroy;
use App\Notifications\ReceivedComment as Received;

class CommentControlelr extends ApiController
{
  protected $comment;

  public function __construct(CommentRepository $comment) {
    parent::__construct();
    $this->comment = $comment;
  }

  public function index()
  {
    return $this->response->collection($this->comment->page());
  }

  public function store(CommentRequest $request)
  {
    $data = array_merge($request->all(), [
      'user_id' => Auth::user()->id
    ]);

    $comment = $this->comment->store($data);

    $comment->commentable->user->notify(new Received($comment));

    return $this->response->item($comment);
  }
}
