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
     * @param  array $columns
     *
     * @return \Illuminate\Support\Collection
     */
    public function all()
    {
        $items = new Collection;

        $tags = Tag::orderBy('name', 'asc')->get();

        foreach ($tags as $tag) {
            $entity = (new TagEntity)->setRawAttributes($item)->setExists(true);
            $items[] = $entity;
        }

        return $items;
    }

}
