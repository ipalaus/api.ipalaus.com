<?php

namespace Isern\Storage\Post;

use Isern\Core\Eloquent;

class PostEloquent extends Eloquent
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'posts';

    /**
     * Define an inverse one-to-one with categories.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function category()
    {
        return $this->belongsTo('Isern\Storage\Category\CategoryEloquent', 'category_id');
    }

    /**
     * Define a many-to-many relationship with tags.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tags()
    {
        return $this->belongsToMany('Isern\Storage\Tag\TagEloquent', 'post_tags', 'post_id', 'tag_id')
            ->withTimestamps();
    }

}
