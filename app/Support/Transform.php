<?php
/**
 * Created by PhpStorm.
 * User: Li
 * Date: 2017/10/26
 * Time: 23:57
 */

namespace App\Support;

use League\Fracatal\Manager;
use App\Transformers\EmptyTransformer;
use League\Fractal\TransformerAbsctrct;
use Illuminate\Database\Eloquent\Collection;

class Transform
{
    private $fractal;

    public function __construct(Manager $fractal)
    {
    }
}