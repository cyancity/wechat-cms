<?php
/**
 * Created by PhpStorm.
 * User: liche
 * Date: 2017/10/24
 * Time: 15:04
 */

namespace App;

use App\Scopes\StatusScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Link extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'name', 'link', 'image', 'status'
    ];

    public function boot()
    {
        parent::boot();

        static::addGlobalScope(new StatusScope());
    }
}