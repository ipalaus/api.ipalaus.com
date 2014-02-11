<?php

namespace Isern\Services;

use Isern\Core\ErrorsTrait;
use Isern\Storage\Tag\TagRepositoryInterface;

class TagService
{

    use ErrorsTrait;

    /**
     * Tag repository implementation.
     *
     * @var \Isern\Storage\Tag\TagRepositoryInterface
     */
    protected $repository;

    /**
     * Create a new TagService instance.
     *
     * @param \Isern\Storage\Tag\TagRepositoryInterface $repository
     */
    public function __construct(TagRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Returns a collection of all the available tags.
     *
     * @return \Illuminate\Support\Collection
     */
    public function all()
    {
        return $this->repository->getAll();
    }

    /**
     * Returns a specified tag by it's slug or fails.
     *
     * @param  string $slug
     *
     * @return \Isern\Storage\Tag\TagEntity|boolean
     */
    public function findBySlug($slug)
    {
        $tag = $this->repository->getBySlug($slug);

        if ( ! $tag) {
            $this->setErrors('not_found');
            return false;
        }

        return $tag;
    }

}
