<?php

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->truncate();

        $super = new Role();
        $super->name = "super";
        $super->display_name = "Super Admin";
        $super->save();

        $admin = new Role();
        $admin->name = "admin";
        $admin->display_name = "Admin";
        $admin->save();

        $editor = new Role();
        $editor->name = "editor";
        $editor->display_name = "Editor";
        $editor->save();

        $author = new Role();
        $author->name = "author";
        $author->display_name = "Author";
        $author->save();

        $basic = new Role();
        $basic->name = "member";
        $basic->display_name = "Member";
        $basic->save();

        $user = User::find(1);
        $user->detachRole($admin);
        $user->attachRole($admin);

        $user2 = User::find(2);
        $user2->detachRole($editor);
        $user2->attachRole($editor);

        $user3 = User::find(3);
        $user3->detachRole($author);
        $user3->attachRole($author);


    }
}
