<?php

namespace Isern\Transformers;

use League\Fractal\TransformerAbstract;
use Isern\Storage\Category\CategoryEntity;

class CategoryTransformer extends TransformerAbstract
{

    /**
     * Turn a category entity into a generic array.
     *
     * @param  \Isern\Storage\Category\CategoryEntity $category
     *
     * @return array
     */
    public function transform(CategoryEntity $category)
    {
        return [
            'id'   => (int) $category->id,
            'name' => $category->name,
            'slug' => $category->slug,
        ];
    }

}
