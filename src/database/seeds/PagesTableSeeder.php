<?php

use Faker\Factory;
use Illuminate\Database\Seeder;
use Keyhunter\Administrator\Model\Page;

class PagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('page_translations')->delete();
        \DB::table('pages')->delete();

        $fake = Factory::create();
       
        $pages = [];
        for($i = 0; $i <= 3; $i++) {
            $pages[] = Page::create([
                'slug' => $fake->word,
                'active' => 1
            ]);
        }

        \Keyhunter\Multilingual\Language::whereActive(1)
            ->get()
            ->each(function ($language) use ($pages, $fake){
                array_walk($pages, function ($page) use ($language, $fake) {
                    \Keyhunter\Administrator\Model\PageTranslation::create([
                        'language_id' => $language->id,
                        'page_id' => $page->id,
                        'title' => $fake->title,
                        'body' => $fake->text(150)
                    ]);
                });
            });
    }
}