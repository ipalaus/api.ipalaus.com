<?php

class DatabaseSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (App::environment() === 'production') {
            exit('Easy boy, easy... do not truncate the tables on production please.');
        }

        $this->call('CategoriesTableSeeder');
        $this->call('TagsTableSeeder');
    }

}
