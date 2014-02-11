<?php

namespace Isern\Storage\Tag;

interface TagRepositoryInterface
{

    public function create(TagEntity $entity);

    public function getAll();

}
