<?php

namespace Isern\Storage\Category;

use Illuminate\Support\Str;
use Isern\Core\Entity;

class CategoryEntity extends Entity
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * Save the name straight to the entity but mutate the slug attribute.
     *
     * @param string $name
     */
    protected function setNameAttribute($name)
    {
    	$this->attributes['name'] = $name;
    	$this->attributes['slug'] = Str::slug($name);
    }

}
