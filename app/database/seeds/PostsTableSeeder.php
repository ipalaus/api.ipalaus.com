<?php

use Isern\Storage\Post\PostEntity;

class PostsTableSeeder extends DatabaseSeeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $repository = app('Isern\Storage\Post\PostRepositoryInterface');
        $categoriesRepository = app('Isern\Storage\Category\CategoryRepositoryInterface');
        $tagsRepository = app('Isern\Storage\Tag\TagRepositoryInterface');

        $categories = $categoriesRepository->getAll();
        $tags = $tagsRepository->getAll();

        $faker = Faker\Factory::create();

        $posts = [
            [
                'title'       => 'Hello World!',
                'body'        => $faker->text,
                'category_id' => '1',
                'tags_id'     => [1, 2, 3],
            ],
        ];

        for ($i = 0; $i < 10; $i++) {
            $category = $faker->randomElement($categories->toArray());
            $tag_id = [];

            for ($c = 0; $c < $faker->randomNumber(0, 4); $c++) {
                $id = $faker->randomElement($tags->toArray())->id;

                if ( ! in_array($id, $tag_id)) {
                    $tag_id[] = $id;
                }
            }

            $posts[] = [
                'title'       => $faker->sentence,
                'body'        => $faker->text,
                'category_id' => $category->id,
                'tags_id'     => $tag_id,
            ];
        }

        foreach ($posts as $post) {
            $entity = new PostEntity($post);
            $repository->create($entity, $post['tags_id']);
        }
    }

}
