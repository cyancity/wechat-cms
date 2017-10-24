<?php
/**
 * Created by PhpStorm.
 * User: liche
 * Date: 2017/10/24
 * Time: 14:57
 */

namespace App;

use App\Scopes\StatusScope;
use App\Tools\Markdowner;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Discussion extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'user_id',
        'last_user_id',
        'title',
        'content',
        'status'
    ];

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope(new StatusScope());
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
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