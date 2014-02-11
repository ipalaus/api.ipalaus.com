<?php

namespace Isern\Storage\Category;

use Illuminate\Support\Collection;
use Isern\Storage\Category\CategoryEloquent as Category;

class EloquentCategoryRepository implements CategoryRepositoryInterface
{

    /**
     * Save a new Category entity and return the instance.
     *
     * @param  \Isern\Storage\Category\CategoryEntity $entity
     *
     * @return \Isern\Storage\Category\CategoryEntity|boolean
     */
    public function create(CategoryEntity $entity)
    {
        $item = new Category($entity);

        if ($item->save()) {
            $entity = (new CategoryEntity)->setRawAttributes($item)->setExists(true);
            return $entity;
        }

        return false;
    }

    /**
     * Get all of the categories.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getAll()
    {
        $items = new Collection;

        $categories = Category::orderBy('name', 'asc')->get();

        foreach ($categories as $item) {
            $entity = (new CategoryEntity)->setRawAttributes($item);
            $entity->setExists(true);

            $items[] = $entity;
        }

        return $items;
    }

    /**
     * Get a category by it's slug.
     *
     * @param  string $slug
     *
     * @return \Isern\Storage\Category\CategoryEntity|null
     */
    public function getBySlug($slug)
    {
        $item = Category::where('slug', $slug)->first();

        if ( ! is_null($item)) {
            $entity = (new CategoryEntity)->setRawAttributes($item);
            $entity->setExists(true);

            return $entity;
        }

        return null;
    }

}
