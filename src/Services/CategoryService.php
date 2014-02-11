<?php

namespace Isern\Services;

use Isern\Core\ErrorsTrait;
use Isern\Storage\Category\CategoryRepositoryInterface;

class CategoryService
{

    use ErrorsTrait;

    /**
     * Category repository implementation.
     *
     * @var \Isern\Storage\Category\CategoryRepositoryInterface
     */
    protected $repository;

    /**
     * Create a new CategoryService instance.
     *
     * @param \Isern\Storage\Category\CategoryRepositoryInterface $repository
     */
    public function __construct(CategoryRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Returns a collection of all the available categories.
     *
     * @return \Illuminate\Support\Collection
     */
    public function all()
    {
        return $this->repository->getAll();
    }

    /**
     * Returns a specified category by it's slug or fails.
     *
     * @param  string $slug
     *
     * @return \Isern\Storage\Category\CategoryEntity|boolean
     */
    public function findBySlug($slug)
    {
        $category = $this->repository->getBySlug($slug);

        if ( ! $category) {
            $this->setErrors('not_found');
            return false;
        }

        return $category;
    }

}
