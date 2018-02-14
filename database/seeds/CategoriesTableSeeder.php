<?php

use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // reset users table
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('categories')->truncate();

        $faker = Faker\Factory::create();

        App\Models\Core\Blog\Category::create([
            'title' => 'Web Design',
            'slug' => 'web-design',
        ]);

        App\Models\Core\Blog\Category::create([
            'title' => 'Web Programming',
            'slug' => 'web-programming',
        ]);

        App\Models\Core\Blog\Category::create([
            'title' => 'Internet',
            'slug' => 'internet',
        ]);

        App\Models\Core\Blog\Category::create([
            'title' => 'Marketing',
            'slug' => 'marketing',
        ]);

        App\Models\Core\Blog\Category::create([
            'title' => 'PHP',
            'slug' => 'php',
        ]);

    }
}
