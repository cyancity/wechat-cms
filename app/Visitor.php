<?php
/**
 * Created by PhpStorm.
 * User: liche
 * Date: 2017/10/24
 * Time: 17:38
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    protected $fillable = [
        'ip', 'article_id', 'clicks'
    ];

    public function article()
    {
        return $this->belongsTo(Article::class);
    }
}