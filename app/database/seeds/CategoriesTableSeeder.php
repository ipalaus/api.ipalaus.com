<?php

use Isern\Storage\Category\CategoryEntity;

class CategoriesTableSeeder extends DatabaseSeeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $repository = app('Isern\Storage\Category\CategoryRepositoryInterface');

        $categories = ['Web Development', 'Mobile Development', 'Travels'];

        foreach ($categories as $category) {
            $entity = new CategoryEntity;
            $entity->name = $category;

            $repository->create($entity);
        }
    }

}
