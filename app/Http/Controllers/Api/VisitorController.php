<?php
/**
 * Created by PhpStorm.
 * User: chengyang
 * Date: 2017/10/30
 * Time: 下午7:38
 */

namespace App\Http\Controllers\Api;

use App\Repositories\VisitorRepository;

class VisitorController
{
    protected $visitor;

    public function __construct(VisitorRepository $visitor)
    {
        parent::__construct();

        $this->visitor = $visitor;
    }

    public function index()
    {
        return $this->response->collection($this->visitor->page());
    }
}