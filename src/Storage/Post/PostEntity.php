<?php

namespace Isern\Storage\Post;

use Illuminate\Support\Str;
use Isern\Core\Entity;

class PostEntity extends Entity
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'category_id', 'body'];

    /**
     * Save the title straight to the entity but mutate the slug attribute.
     *
     * @param  string $title
     */
    protected function setTitleAttribute($title)
    {
    	$this->attributes['title'] = $title;
    	$this->attributes['slug'] = Str::slug($title);
    }

}
