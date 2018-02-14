<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(PermissionsTableSeeder::class);

        // $this->call(CategoriesTableSeeder::class);
        // $this->call(TagsTableSeeder::class);
        // $this->call(PostsTableSeeder::class);
        $this->call(AdminMenuTableSeeder::class);
        $this->call(SiteSettingsTableSeeder::class);
        // $this->call(SidebarLayoutSeeder::class);
        $this->call(TopNavWideLayoutSeeder::class);
        $this->call(DarkThemeCssSeeder::class);
        $this->call(ContentTypeSeeder::class);

        // $this->call(PagesTableSeeder::class);

    }
}
