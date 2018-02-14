<?php

use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permissions')->truncate();
        DB::table('permission_role')->truncate();
        DB::table('permission_user')->truncate();

        // crud users
        $crudUser = new Permission();
        $crudUser->name = "crud-user";
        $crudUser->display_name = "Manage Users";
        $crudUser->save();

        // crud roles
        $crudRole = new Permission();
        $crudRole->name = "crud-role";
        $crudRole->display_name = "Manage Roles";
        $crudRole->save();

        // crud permissions
        $crudPermission = new Permission();
        $crudPermission->name = "crud-permission";
        $crudPermission->display_name = "Manage Permissions";
        $crudPermission->save();

        // crud posts
        $crudPost = new Permission();
        $crudPost->name = "crud-post";
        $crudPost->display_name = "Manage Posts";
        $crudPost->save();

        // crud categories
        $crudCategory = new Permission();
        $crudCategory->name = "crud-category";
        $crudCategory->display_name = "Manage Categories";
        $crudCategory->save();

        // crud tags
        $crudTag = new Permission();
        $crudTag->name = "crud-tag";
        $crudTag->display_name = "Manage Tags";
        $crudTag->save();

        // crud comments
        $crudComments = new Permission();
        $crudComments->name = "crud-comments";
        $crudComments->display_name = "Manage Comments";
        $crudComments->save();

        // post comments
        $postComments = new Permission();
        $postComments->name = "post-comments";
        $postComments->display_name = "Post Comments";
        $postComments->save();

        $testComments = new Permission();
        $testComments->name = "test-comments";
        $testComments->display_name = "Test Comments";
        $testComments->save();

        // attach permissions
        $super = Role::whereName('super')->firstOrFail();
        $admin = Role::whereName('admin')->firstOrFail();
        $editor = Role::whereName('editor')->firstOrFail();
        $author = Role::whereName('author')->firstOrFail();
        $member = Role::whereName('member')->firstOrFail();
        //
        $super->attachPermissions([$crudUser, $crudRole, $crudPermission, $crudPost, $crudCategory, $crudTag, $crudComments]);
        $admin->attachPermissions([$crudUser, $crudPost, $crudCategory, $crudTag, $crudComments, $postComments]);
        $editor->attachPermissions([$crudPost, $crudCategory, $crudTag, $crudComments, $postComments]);
        $author->attachPermissions([$crudPost, $postComments]);
        $member->attachPermissions([$postComments]);

        $user = User::find(2);
        $user->attachPermissions([$testComments]);
    }
}
