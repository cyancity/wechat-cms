<?php

namespace App;

use App\Tools\Markdowner;
use Jcc\LaravelVote\CanBeVoted;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use SoftDeletes, CanBeVoted;

    protected $vote = User::class;

    protected $fillable = [
        'user_id', 'commentable_id', 'commentable_type', 'content'
    ];

    protected $dates = ['deleted_at'];

    public function commentable()
    {
        return $this->morphTo();
    }

    public function setContentAttribute($value)
    {
        $data = [
            'raw' => $value,
            'html' => (new Markdowner)->convertMarkdownToHtml($value)
        ];

        $this->attribute['content'] = json_encode($data);
    }
}
