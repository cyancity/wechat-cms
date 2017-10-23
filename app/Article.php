<?php

namespace App;

use App\Scopes\DraftScope;
use App\Tools\Markdowner;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use SoftDeletes;

    protected $dates = ['published_at', 'created_at', 'deleted_at'];

    protected $fillable = [
    	'user_id',
    	'last_user_id',
    	'category_id',
    	'title',
    	'subtitle',
    	'slug',
    	'page_image',
    	'content',
    	'meta_description',
    	'is_draft',
    	'is_original',
    	'published_at'
    ];

    protected $casts = [
    	'content' => 'array'
    ]

    public function boot()
    {
    	parent::boot();
    	static::addGlobalScope(new DraftScope());
    }

    public function user()
    {
    	return $this->belongsTo(User::class);
    }

    public function category()
    {
    	return $this->belongsTo(Category::class);
    }

    public function tags()
    {
    	return $this->morphToMany(Tag::class, 'taggable');
    }

    public function comments()
    {
    	return $this->morphToMany(Comment::class, 'commentable');
    }

    public function config()
    {
    	return $this->morphToMany(Configuration::class, 'configuration');
    }

    public function getCreatedAtAttribute($value)
    {
    	return \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $value)->diffForHumans();
    }

    public function setTitleAttribute($value)
    {
    	$this->attribute['title'] = $value;
    	if (!config('services.youdao.appKey') || !config('services.youdao.appSecret')) {
    		$this->setUniqueSlug($value, str_random(5));
    	} else {
    		$this->setUniqueSlug(translug($value), '');
    	}
    }

    public function setUniqueSlug($value, $payload)
    {
    	$slug = str_slug($value.'-'.$payload);

    	if (static::whereSlug($slug)->exists()) {
    		$this->setUniqueSlug($slug, (int) $payload + 1);
    		return;
    	}

    	$this->attributes['slug'] = $slug;
    }

    public function setContentAttribute($value)
    {
    	$data = [
    		'raw' => $value,
    		'html' => (new Markdowner)->convertMarkdownToHtml($value)
    	];
    	$this->attributes['content'] = json_encode($data)
    }

}
