<?php
/**
 * Created by PhpStorm.
 * User: liche
 * Date: 2017/10/24
 * Time: 17:34
 */

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tag extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillables = [
        'tag', 'title', 'subtitle', 'meta_description'
    ];

    public function articles()
    {
        return $this->morphedByMany(Article::class, 'taggable');
    }

    public function discussions()
    {
        return $this->morphedByMany(Discussion::class, 'taggable');
    }
}