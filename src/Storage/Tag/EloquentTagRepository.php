<?php

namespace Isern\Storage\Tag;

use Illuminate\Support\Collection;
use Isern\Storage\Tag\TagEloquent as Tag;

class EloquentTagRepository implements TagRepositoryInterface
{

    /**
     * Save a new Tag entity and return the instance.
     *
     * @param  \Isern\Storage\Tag\TagEntity $entity
     *
     * @return \Isern\Storage\Tag\TagEntity|boolean
     */
    public function create(TagEntity $entity)
    {
        $item = new Tag($entity);

        if ($item->save()) {
            $entity = (new TagEntity)->setRawAttributes($item)->setExists(true);
            return $entity;
        }

        return false;
    }

    /**
     * Get all of the Tags.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getAll()
    {
        $items = new Collection;

        $tags = Tag::orderBy('name', 'asc')->get();

        foreach ($tags as $item) {
            $entity = (new TagEntity)->setRawAttributes($item);
            $entity->setExists(true);

            $items[] = $entity;
        }

        return $items;
    }

    /**
     * Get a tag by it's slug.
     *
     * @param  string $slug
     *
     * @return \Isern\Storage\Tag\TagEntity|null
     */
    public function getBySlug($slug)
    {
        $item = Tag::where('slug', $slug)->first();

        if ( ! is_null($item)) {
            $entity = (new TagEntity)->setRawAttributes($item);
            $entity->setExists(true);

            return $entity;
        }

        return null;
    }

}
