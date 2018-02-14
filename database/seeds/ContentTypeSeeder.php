<?php

use Illuminate\Database\Seeder;

class ContentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\Models\Core\Content\ContentType::create([
            'name' => 'Pages',
            'name_singular' => 'Page',
            'slug' => 'pages'
        ]);
    }
}
