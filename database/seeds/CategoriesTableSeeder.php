<?php

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $category = new Category();
        $category->truncate();
        $category->name = '默认分类';
        $category->description = '默认分类';
        $category->ip = '127.0.0.1';
        $category->save();

    }
}
