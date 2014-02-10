<?php

use Isern\Storage\Tag\TagEntity;

class TagsTableSeeder extends DatabaseSeeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $repository = app('Isern\Storage\Tag\TagRepositoryInterface');

        $tags = ['PHP', 'Laravel', 'Scaling', 'High Availability', 'JavaScript', 'AngularJS'];

        foreach ($tags as $tag) {
            $entity = new TagEntity;
            $entity->name = $tag;

            $repository->create($entity);
        }
    }

}
