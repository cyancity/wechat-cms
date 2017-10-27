<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArticleRequest extends FormRequest
{
  public function authorize()
  {
    return true;
  }

  public function rules()
  {
    return [
      'title' => 'required|min:3',
      'subtitle' => 'required|min:3',
      'content'       => 'required',
      'category_id'   => 'required',
      'published_at'  => 'required'
    ]
  }
}
