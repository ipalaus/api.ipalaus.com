<?php

use Isern\Services\PostService;
use Isern\Transformers\PostTransformer;

class PostsController extends ApiController
{

    /**
     * Available scopes in the controller.
     *
     * @var array
     */
    protected $availableScopes = ['category', 'tags'];

    /**
     * Post service layer.
     *
     * @var \Isern\Services\PostService
     */
    protected $postService;

    /**
     * Create a new PostsController instance.
     *
     * @param \Isern\Services\PostService $service
     */
    public function __construct(PostService $service)
    {
        $this->postService = $service;

        parent::__construct();
    }

    /**
     * Display a listing of posts using a cursor.
     *
     * @return Response
     */
    public function index()
    {
        $current = (int) Input::get('cursor', false);
        $posts   = $this->postService->cursor(8, $current, $this->getRequestedScopes());
        $next    = ($last = $posts->last()) ? $last->id : 0;

        return $this->respondWithCursor($posts, new PostTransformer, $current, $next);
    }

    /**
     * Display the specified post.
     *
     * @param  string $slug
     *
     * @return Response
     */
    public function show($slug)
    {
        $post = $this->postService->findBySlug($slug, $this->getRequestedScopes());

        if ($post) {
            return $this->respondWithItem($post, new PostTransformer);
        }

        return $this->guessError($this->postService->getErrors());
    }

}
