<?php

namespace Database\Seeders;

use App\Models\Follow;
use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use App\Models\Creator;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement("SET FOREIGN_KEY_CHECKS = 0");

        User::truncate();
        User::flushEventListeners();
        $usersQuantity = 100;
        User::factory($usersQuantity)->create();

        Category::truncate();
        Category::flushEventListeners();
        $categoriesQuantity = 5;
        Category::factory($categoriesQuantity)->create();

        Creator::truncate();
        Creator::flushEventListeners();
        $creatorsQuantity = 5;
        Creator::factory($creatorsQuantity)->create();

        Post::truncate();
        Post::flushEventListeners();
        $postsQuantity = 100;
        Post::factory($postsQuantity)->create();

        Follow::truncate();
        Follow::flushEventListeners();
        $followsQuantity = 30;
        Follow::factory($followsQuantity)->create();

        Like::truncate();
        Like::flushEventListeners();
        $likesQuantity = 30;
        Like::factory($likesQuantity)->create();

        /* Category_Creator::truncate();
        Category_Creator::flushEventListeners();
        $creatorsQuantity = 5;
        Category_Creator::factory($creatorsQuantity)->create(); */
    }
}
