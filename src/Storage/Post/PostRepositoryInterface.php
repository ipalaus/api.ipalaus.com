<?php

namespace Isern\Storage\Post;

interface PostRepositoryInterface
{

    public function create(PostEntity $entity, array $tags = []);
    public function take($take = 8, $cursor = false, array $with = []);
    public function getBySlug($slug);

}
