<?php

use Isern\Services\TagService;
use Isern\Transformers\TagTransformer;

class TagsController extends ApiController
{

    /**
     * Tag service layer.
     *
     * @var \Isern\Services\TagService
     */
    protected $tagService;

    /**
     * Create a new TagsController instance.
     *
     * @param \Isern\Services\TagService $service
     */
    public function __construct(TagService $service)
    {
        $this->tagService = $service;

        parent::__construct();
    }

    /**
     * Display a listing of tags.
     *
     * @return Response
     */
    public function index()
    {
        $tags = $this->tagService->all();

        return $this->respondWithCollection($tags, new TagTransformer);
    }

    /**
     * Display the specified tag.
     *
     * @param  string $slug
     *
     * @return Response
     */
    public function show($slug)
    {
        $tag = $this->tagService->findBySlug($slug);

        if ($tag) {
            return $this->respondWithItem($tag, new TagTransformer);
        }

        return $this->guessError($this->tagService->getErrors());
    }

}
