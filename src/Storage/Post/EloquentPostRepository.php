<?php

namespace Isern\Storage\Post;

use Illuminate\Support\Collection;
use Isern\Storage\Category\CategoryEntity;
use Isern\Storage\Post\PostEloquent as Post;
use Isern\Storage\Tag\TagEntity;

class EloquentPostRepository implements PostRepositoryInterface
{

    /**
     * Save a new Post entity and return the instance.
     *
     * @param  \Isern\Storage\Post\PostEntity $entity
     * @param  array                          $tags
     *
     * @return \Isern\Storage\Post\PostEntity|boolean
     */
    public function create(PostEntity $entity, array $tags = array())
    {
        $item = new Post($entity);

        if ($item->save()) {
            if ( ! empty($tags)) {
                $item->tags()->attach($tags);
            }

            $entity = (new PostEntity)->setRawAttributes($item)->setExists(true);
            return $entity;
        }

        return false;
    }

    /**
     * Get posts from a given cursor limiting the results.
     *
     * @param  integer $take
     * @param  mixed   $cursor
     * @param  array   $with
     *
     * @return \Illuminate\Support\Collection
     */
    public function take($take = 8, $cursor = false, array $with = [])
    {
        $items = new Collection;

        $posts = Post::with($with)->take($take)->orderBy('id');

        if ($cursor) {
            $posts = $posts->where('id', '>', $cursor);
        }

        $posts = $posts->get();

        foreach ($posts as $post) {
            $entity = (new PostEntity)->setRawAttributes($post);
            $entity->setExists(true);

            // belongs to category
            if (in_array('category', $with)) {
                $entity->category = (new CategoryEntity)->setRawAttributes($post->category);
                $entity->category->setExists(true);
            }

            // belongs to many tags
            if (in_array('tags', $with)) {
                $tags = new Collection;

                foreach ($post->tags as $tag) {
                    $tag = (new TagEntity)->setRawAttributes($tag);
                    $tag->setExists(true);

                    $tags[] = $tag;
                }

                $entity->tags = $tags;
            }

            $items[] = $entity;
        }

        return $items;
    }

    /**
     * Get a Post by it's slug.
     *
     * @param  string $slug
     *
     * @return \Isern\Storage\Post\PostEntity|null
     */
    public function getBySlug($slug)
    {
        $item = Post::where('slug', $slug)->first();

        if ( ! is_null($item)) {
            $entity = (new PostEntity)->setRawAttributes($item);
            $entity->setExists(true);

            return $entity;
        }

        return null;
    }

}
