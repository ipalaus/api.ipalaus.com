<?php

use Isern\Services\CategoryService;
use Isern\Transformers\CategoryTransformer;

class CategoriesController extends ApiController
{

    /**
     * Category service layer.
     *
     * @var \Isern\Services\CategoryService
     */
    protected $categoryService;

    /**
     * Create a new CategoriesController instance.
     *
     * @param \Isern\Services\CategoryService $service
     */
    public function __construct(CategoryService $service)
    {
        $this->categoryService = $service;

        parent::__construct();
    }

    /**
     * Display a listing of categories.
     *
     * @return Response
     */
    public function index()
    {
        $categories = $this->categoryService->all();

        return $this->respondWithCollection($categories, new CategoryTransformer);
    }

    /**
     * Display the specified category.
     *
     * @param  string $slug
     *
     * @return Response
     */
    public function show($slug)
    {
        $category = $this->categoryService->findBySlug($slug);

        if ($category) {
            return $this->respondWithItem($category, new CategoryTransformer);
        }

        return $this->guessError($this->categoryService->getErrors());
    }

}
