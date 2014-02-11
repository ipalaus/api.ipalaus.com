<?php

namespace Isern\Storage\Category;

interface CategoryRepositoryInterface
{

    public function create(CategoryEntity $entity);
    public function getAll();
    public function getBySlug($slug);

}
