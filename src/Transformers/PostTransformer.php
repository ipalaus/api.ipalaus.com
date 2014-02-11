<?php

namespace Isern\Transformers;

use League\Fractal\TransformerAbstract;
use Isern\Storage\Post\PostEntity;

class PostTransformer extends TransformerAbstract
{

    /**
     * Available embeds if requested.
     *
     * @var array
     */
    protected $availableEmbeds = ['category', 'tags'];

    /**
     * Turn a post entity into a generic array.
     *
     * @param  \Isern\Storage\Post\PostEntity $post
     *
     * @return array
     */
    public function transform(PostEntity $post)
    {
        return [
            'id'          => (int) $post->id,
            'category_id' => (int) $post->category_id,
            'title'       => $post->title,
            'slug'        => $post->slug,
            'body'        => $post->body,
            'created_at'  => (string) $post->created_at,
        ];
    }

     /**
     * Embed data for category scope.
     *
     * @param  \Isern\Storage\Post\PostEntity $post
     *
     * @return \League\Fractal\Resource\Item
     */
    public function embedCategory(PostEntity $post)
    {
        return $this->item($post->category, new CategoryTransformer);
    }

    /**
     * Embed data for tags scope.
     *
     * @param  \Isern\Storage\Post\PostEntity $post
     *
     * @return \League\Fractal\Resource\Item
     */
    public function embedTags(PostEntity $post)
    {
        return $this->collection($post->tags, new TagTransformer);
    }

}
