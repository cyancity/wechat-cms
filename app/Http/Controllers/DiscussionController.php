<?php
/**
 * Created by PhpStorm.
 * User: chengyang
 * Date: 2017/10/30
 * Time: 下午7:43
 */

namespace App\Http\Controllers;

use App\Http\Requests\DiscussionRequest;
use App\Repositories\DiscussionRepository;
use App\Repositories\TagRepository;

class DiscussionController extends Controller
{
    protected $discussion;

    protected $tag;

    public function __construct(DiscussionRepository $discussion, TagRepository $tag)
    {
        $this->middleware('auth')->except(['index', 'show']);

        $this->discussion = $discussion;

        $this->tag = $tag;
    }

    public function index()
    {
        $discussions = $this->discussion->page(config('blog.discussion.number'), config('blog.discussion.sort'), config('blog.discussion.sortColumn'));

        return view('discussion.index', compact('discussions'));
    }

    public function create()
    {
        $tags = $this->tag->all();

        return view('discussion.create', compact('tags'));
    }

    public function stroe(DiscussionRequest $request)
    {
        $data = array_merge($request->all(), [
            'user_id' => \Auth::id(),
            'last_user_id' => \Auth::id(),
            'status' => true
        ]);

        $this->discussion->store($data);

        return redirect()->to('discussion');
    }

    public function show($id)
    {
        $discussion = $this->discussion->getById($id);

        return view('discussion.show', compact('discussion'));
    }

    public function edit($id)
    {
        $discussion = $this->discussion->getById($id);

        $this->authorize('update', $discussion);

        $tags = $this->tag->all();

        $selectTags = $this->discussion->listTagsIdsForDiscussion($discussion);

        return view('discussion.edit', compact('discussion', 'tags', 'selectTags'));
    }

    public function update(DiscussionRequest $request, $id)
    {
        $discussion = $this->discussion->getById($id);

        $this->authorize('update', $discussion);

        $data = array_merge($request->all(), [
            'last_user_id' => \Auth::id()
        ]);

        $this->discussion->update($id, $data);

        return redirect()->to("discussion/{$id}");
    }
}