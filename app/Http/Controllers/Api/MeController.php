<?php
/**
 * Created by PhpStorm.
 * User: chengyang
 * Date: 2017/10/29
 * Time: 下午8:08
 */

namespace App\Http\Controllers\Api;

use  Illuminate\Http\Request;
use App\Repositories\CommentRepository;

class MeController extends ApiController
{
    protected $comment;

    public function __construct(CommentRepository $comment)
    {
        parent::__construct();

        $this->comment = $comment;
    }

    public function postVoteComment(Request $request, $type)
    {
        $this->validate($request, [
            'id' => 'required|exists:comments,id'
        ]);

        ($type == 'up')
            ? $this->comment->toggleVote($request->id)
            : $this->comment->toggleVote($request->id, false);

        return $this->response->withNoContent();
    }

}