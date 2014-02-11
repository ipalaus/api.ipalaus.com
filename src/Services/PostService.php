<?php

namespace Isern\Services;

use Isern\Core\ErrorsTrait;
use Isern\Storage\Post\PostRepositoryInterface;

class PostService
{

    use ErrorsTrait;

    /**
     * Post repository implementation.
     *
     * @var \Isern\Storage\Post\PostRepositoryInterface
     */
    protected $repository;

    /**
     * Create a new PostService instance.
     *
     * @param \Isern\Storage\Post\PostRepositoryInterface $repository
     */
    public function __construct(PostRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Returns posts based on the current cursor.
     *
     * @param  integer $take
     * @param  integer $currentPosition
     * @param  array   $scopes
     *
     * @return \Illuminate\Support\Collection
     */
    public function cursor($take = 8, $currentPosition = 0, $scopes = [])
    {
        return $this->repository->take($take, $currentPosition, $scopes);
    }

    /**
     * Returns a specified post by it's slug or fails.
     *
     * @param  string $slug
     *
     * @return \Isern\Storage\Post\PostEntity|boolean
     */
    public function findBySlug($slug)
    {
        $post = $this->repository->getBySlug($slug);

        if ( ! $post) {
            $this->setErrors('not_found');
            return false;
        }

        return $category;
    }

}
