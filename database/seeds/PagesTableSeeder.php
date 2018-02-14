<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use GrahamCampbell\Markdown\Facades\Markdown;

class PagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // reset pages table
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('pages')->truncate();

        $faker = Faker\Factory::create('en_US');
        $faker->addProvider(new \DavidBadura\FakerMarkdownGenerator\FakerProvider($faker));
        $date = Carbon::create(2017, 3, 6, 9);

        for ($i=0; $i < 5; $i++) {

            $date->addHours(1);
            $publishedDate = clone($date);
            $published_at = $i > 5 && rand(0, 1) == 0 ? NULL : $publishedDate->addHours($i + rand(4, 20));
            $published = false;
            if($published_at)
                $published = true;

            $markdown = $faker->markdownP(300) ."\n" . $faker->markdownBulletedList() ."\n" . $faker->markdownH3() ."\n" . $faker->markdownP(350) ."\n" . $faker->markdownInlineImg('img-responsive');
            $body = Markdown::convertToHtml($markdown);

            App\Models\Core\Pages\Page::create([
                'title' => $faker->sentence(rand(8, 12)),
                'user_id' => rand(1,5),
                'body' => $body,
                'markdown' => $markdown,
                'slug' => $faker->slug(),
                'created_at' => clone($date),
                'updated_at' => clone($date),
                'published_at' => $published_at,
                'published' => $published,
                'views' => rand(1, 10) * rand(5, 10),
            ]);
        }
    }
}
