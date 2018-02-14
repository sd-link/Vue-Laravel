<?php

use Illuminate\Database\Seeder;

class TagsTableSeeder extends Seeder
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
        DB::table('tags')->truncate();

        App\Models\Core\Blog\Tag::create([
            'title' => 'laravel',
            'slug' => 'laravel',
        ]);

        App\Models\Core\Blog\Tag::create([
            'title' => 'php',
            'slug' => 'php',
        ]);

        App\Models\Core\Blog\Tag::create([
            'title' => 'javascript',
            'slug' => 'javascript',
        ]);

        App\Models\Core\Blog\Tag::create([
            'title' => 'ruby on rails',
            'slug' => 'ruby-on-rails',
        ]);

        App\Models\Core\Blog\Tag::create([
            'title' => 'jQuery',
            'slug' => 'jquery',
        ]);
    }
}
