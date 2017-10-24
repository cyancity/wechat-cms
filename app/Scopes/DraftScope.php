<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Builder;


/**
 * summary
 */
class DraftScope implements Scope  
{
    /**
     * summary
     */
    public function apply(Builder $builder, Model $Model)
    {
        $builder->where('is_draft', 0);
    }
}
