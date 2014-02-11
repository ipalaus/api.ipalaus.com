<?php

Route::get('categories',        ['uses' => 'CategoriesController@index', 'as' => 'categories.index']);
Route::get('categories/{slug}', ['uses' => 'CategoriesController@show',  'as' => 'categories.show']);

Route::get('posts',        ['uses' => 'PostsController@index', 'as' => 'posts.index']);
Route::get('posts/{slug}', ['uses' => 'PostsController@show',  'as' => 'posts.show']);

Route::get('tags',        ['uses' => 'TagsController@index', 'as' => 'tags.index']);
Route::get('tags/{slug}', ['uses' => 'TagsController@show',  'as' => 'tags.show']);
