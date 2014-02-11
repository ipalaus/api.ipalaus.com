<?php

namespace Isern\Transformers;

use League\Fractal\TransformerAbstract;
use Isern\Storage\Tag\TagEntity;

class TagTransformer extends TransformerAbstract
{

    /**
     * Turn a tag entity into a generic array.
     *
     * @param  \Isern\Storage\Tag\TagEntity $tag
     *
     * @return array
     */
    public function transform(TagEntity $tag)
    {
        return [
            'id'   => (int) $tag->id,
            'name' => $tag->name,
            'slug' => $tag->slug,
        ];
    }

}
