<?php
/**
 * Created by PhpStorm.
 * User: chengyang
 * Date: 2017/10/30
 * Time: 下午7:40
 */

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Repositories\CategoryRepository;

class CategoryController extends Controller
{
    protected $category;

    public function __construct(CategoryRepository $category)
    {
        $this->category = $category;
    }

    public function index()
    {
        $categories = $this->category->all();

        return view('category.index', compact('categories'));
    }

    public function show($category)
    {
        if (!$category = $this->category->getByName($category)) abort(404);

        $articles = $category->articles;

        return view('category.show', compact('category', 'articles'));
    }
}