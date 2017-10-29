<?php

namespace App\Repositories;

use App\Comment;
use App\Tools\Mention;
use App\Notifications\GotVote;
use App\Notifications\MentionedUser;

/**
 * summary
 */
class CommentRepository
{
	use IndexRepository;
    /**
     * summary
     */
    public function __construct(Comment $comment)
    {
        $this->model = $comment;
    }

    public function store($input)
    {
    	$mention = new Mention();

    	$input['content'] = $mention->parse($input['content']);

    	$comment = $this->save($this->model, $input);

    	foreach ($mention->users as $user) {
    		$user->notify(new MentionedUser($comment));
    	}

    	return $comment;
    }

    public function save($model, $input)
    {
    	$model->fill($input);

    	$model->save();

    	return $model;
    }

    public function getByCommentable($commentableId, $commentableType)
    {
    	return $this->model->where('commentable_id', $commentableId)
    				->where('commentable_type', $commentableType)
    				->get();
    }

    public function toggleVote($id, $isUpVote = true)
    {
    	$user = auth()->user();

    	$comment = $this->getById($id);

    	if ($comment == null) {
    		return false;
    	}

    	return $isUpVote
    			? $this->upOrDownVote($user, $comment)
    			: $this->upOrDownVote($user, $comment, 'down');
    }

    public function upOrDownVote($user, $target, $type = 'up')
    {
    	$hasVoted = $user->{'has'.ucfirst($type).'Voted'}($target);

    	// judge whether the user has voted for some comment
    	if (hasVoted) {
 			$user->cancelVote(target);   		
 			return false;
    	}

    	if ($type == 'up') {
    		$target->user->notity(new GotVote($type.'_vote', $user, $target));
    	}

    	$user->{$type.'Vote'}($target);

    	return true;
    }
}