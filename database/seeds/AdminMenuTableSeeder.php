<?php

use Illuminate\Database\Seeder;

class AdminMenuTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('admin_menu')->truncate();

        // AdminMenu::create([
        //     'module' => 'Core.Pages',
        //     'name' => 'Pages',
        //     'menu_id' =>'core.pages',
        //     'icon' => 'fa fa-file-text',
        //     'route' => 'backend.pages.index',
        //     'order' => 10,
        // ]);

        // BLOG
        // AdminMenu::create([
        //     'module' => 'Core.Blog',
        //     'name' => 'Blog',
        //     'menu_id' =>'core.blog',
        //     'icon' => 'fa fa-edit',
        //     'order' => 20,
        // ]);
        //
        // AdminMenu::create([
        //     'module' => 'Core.Blog',
        //     'name' => 'Posts',
        //     'menu_id' =>'core.blog.posts',
        //     'route' => 'backend.blog.index',
        //     'parent' => AdminMenu::where('menu_id', 'core.blog')->value('id'),
        // ]);

        // AdminMenu::create([
        //     'module' => 'Core.Blog',
        //     'name' => 'Create Post',
        //     'menu_id' =>'core.blog.create',
        //     'route' => 'backend.blog.create',
        //     'visible' => '-1',
        //     'parent' => AdminMenu::where('menu_id', 'core.blog')->value('id'),
        // ]);
        //
        // AdminMenu::create([
        //     'module' => 'Core.Blog',
        //     'name' => 'Edit Post',
        //     'menu_id' =>'core.blog.edit',
        //     'route' => 'backend.blog.edit',
        //     'visible' => '-1',
        //     'parent' => AdminMenu::where('menu_id', 'core.blog')->value('id'),
        // ]);

        // AdminMenu::create([
        //     'module' => 'Core.Blog',
        //     'name' => 'Categories',
        //     'menu_id' =>'core.blog.categories',
        //     'route' => 'backend.blog.categories.index',
        //     'parent' => AdminMenu::where('menu_id', 'core.blog')->value('id'),
        // ]);
        //
        // AdminMenu::create([
        //     'module' => 'Core.Blog',
        //     'name' => 'Tags',
        //     'menu_id' =>'core.blog.tags',
        //     'route' => 'backend.blog.tags.index',
        //     'parent' => AdminMenu::where('menu_id', 'core.blog')->value('id'),
        // ]);

        // MEDIA
        AdminMenu::create([
            'module' => 'Core.Media',
            'name' => 'Media',
            'menu_id' =>'core.media',
            'icon' => 'fa fa-image',
            'order' => 30,
        ]);

        // Galleries
        AdminMenu::create([
            'module' => 'Core.Media',
            'name' => 'Galleries',
            'menu_id' =>'core.media.galleries',
            'route' => 'backend.media.galleries.index',
            'parent' => AdminMenu::where('menu_id', 'core.media')->value('id'),
        ]);

        // Create Gallery
        AdminMenu::create([
            'module' => 'Core.Media',
            'name' => 'Create Gallery',
            'menu_id' =>'core.media.galleries.create',
            'route' => 'backend.media.galleries.create',
            'visible' => 0,
            'parent' => AdminMenu::where('menu_id', 'core.media')->value('id'),
        ]);

        // Edit Gallery
        AdminMenu::create([
            'module' => 'Core.Media',
            'name' => 'Edit Gallery',
            'menu_id' =>'core.media.galleries.edit',
            'route' => 'backend.media.galleries.edit',
            'visible' => 0,
            'parent' => AdminMenu::where('menu_id', 'core.media')->value('id'),
        ]);

        // Images
        AdminMenu::create([
            'module' => 'Core.Media',
            'name' => 'Images',
            'menu_id' =>'core.media.images',
            'route' => 'backend.media.images.index',
            'parent' => AdminMenu::where('menu_id', 'core.media')->value('id'),
        ]);

        // Tags
        AdminMenu::create([
            'module' => 'Core.Media',
            'name' => 'Tags',
            'menu_id' =>'core.media.tags',
            'route' => 'backend.media.tags.index',
            'parent' => AdminMenu::where('menu_id', 'core.media')->value('id'),
        ]);

        // FORMS
        AdminMenu::create([
            'module' => 'Core.Forms',
            'name' => 'Forms',
            'menu_id' =>'core.forms',
            'icon' => 'fa fa-wpforms',
            'order' => 30,
        ]);

        AdminMenu::create([
            'module' => 'Core.Forms',
            'name' => 'All Forms',
            'menu_id' =>'core.forms.list',
            'route' => 'backend.forms.index',
            'parent' => AdminMenu::where('menu_id', 'core.forms')->value('id'),
        ]);


        // USERS
        $meta = [];
        $meta['roles'][0] = 'admin';
        $meta['roles'][1] = 'editor';
        $meta['permissions'][0] = 'permission 1';
        $meta['permissions'][1] = 'permission 2';

        AdminMenu::create([
            'module' => 'Core.Users',
            'name' => 'Users',
            'menu_id' =>'core.users',
            'icon' => 'fa fa-users',
            'meta' => json_encode((object)$meta),
            'order' => 30,
        ]);

        AdminMenu::create([
            'module' => 'Core.Users',
            'name' => 'Users',
            'menu_id' =>'core.users.list',
            'route' => 'backend.users.index',
            'parent' => AdminMenu::where('menu_id', 'core.users')->value('id'),
        ]);

        AdminMenu::create([
            'module' => 'Core.Users',
            'name' => 'Roles',
            'menu_id' =>'core.roles',
            'route' => 'backend.roles.index',
            'parent' => AdminMenu::where('menu_id', 'core.users')->value('id'),
        ]);

        AdminMenu::create([
            'module' => 'Core.Users',
            'name' => 'Permissions',
            'menu_id' =>'core.permissions',
            'route' => 'backend.permissions.index',
            'parent' => AdminMenu::where('menu_id', 'core.users')->value('id'),
        ]);

        //Comments
        AdminMenu::create([
            'module' => 'Core.Comments',
            'name' => 'Comments',
            'menu_id' =>'core.comments',
            'icon' => 'fa fa-comments',
            'route' => 'backend.comments.index',
            'order' => 30,
        ]);

        // DESIGN
        AdminMenu::create([
            'module' => 'Core.Design',
            'name' => 'Design',
            'menu_id' =>'core.design',
            'icon' => 'fa fa-desktop',
            'order' => 40,
        ]);

        AdminMenu::create([
            'module' => 'Core.Design',
            'name' => 'Customize',
            'menu_id' =>'core.design.customize',
            'route' => 'backend.design.customize',
            'parent' => AdminMenu::where('menu_id', 'core.design')->value('id'),
        ]);

        AdminMenu::create([
            'module' => 'Core.Design',
            'name' => 'Menus',
            'menu_id' =>'core.design.menus',
            'route' => 'backend.design.menus',
            'parent' => AdminMenu::where('menu_id', 'core.design')->value('id'),
        ]);

        AdminMenu::create([
            'module' => 'Core.Design',
            'name' => 'Themes',
            'menu_id' =>'core.design.themes',
            'route' => 'backend.design.themes',
            'parent' => AdminMenu::where('menu_id', 'core.design')->value('id'),
        ]);

        AdminMenu::create([
            'module' => 'Core.Design',
            'name' => 'Widgets',
            'menu_id' =>'core.design.widgets',
            'route' => 'backend.design.widgets',
            'parent' => AdminMenu::where('menu_id', 'core.design.themes')->value('id'),
        ]);

        // Content
        AdminMenu::create([
            'module' => 'Core.Content.Design',
            'name' => 'Content',
            'menu_id' =>'core.content.design',
            'icon' => 'fa fa-image',
            'order' => 30,
        ]);

        // Types
        AdminMenu::create([
            'module' => 'Core.Content.Design',
            'name' => 'Types',
            'menu_id' =>'core.content.types.index',
            'route' => 'backend.content.types.index',
            'parent' => AdminMenu::where('menu_id', 'core.content.design')->value('id'),
        ]);

        // Templates
        AdminMenu::create([
            'module' => 'Core.Content.Design',
            'name' => 'Templates',
            'menu_id' =>'core.content.templates.index',
            'route' => 'backend.content.templates.index',
            'parent' => AdminMenu::where('menu_id', 'core.content.design')->value('id'),
        ]);

        // Settings
        AdminMenu::create([
            'module' => 'Core.Settings',
            'name' => 'Settings',
            'menu_id' =>'core.settings',
            'icon' => 'fa fa-wpforms',
            'order' => 60,
        ]);

        AdminMenu::create([
            'module' => 'Core.Settings',
            'name' => 'Admin',
            'menu_id' =>'core.settings.admin',
            'route' => 'backend.settings.admin',
            'parent' => AdminMenu::where('menu_id', 'core.settings')->value('id'),
        ]);

        AdminMenu::create([
            'module' => 'Core.Settings',
            'name' => 'Website',
            'menu_id' =>'core.settings.website',
            'route' => 'backend.settings.website',
            'parent' => AdminMenu::where('menu_id', 'core.settings')->value('id'),
        ]);

        AdminMenu::create([
            'module' => 'Core.Settings',
            'name' => 'Members',
            'menu_id' =>'core.settings.members',
            'route' => 'backend.settings.members',
            'parent' => AdminMenu::where('menu_id', 'core.settings')->value('id'),
        ]);

        AdminMenu::create([
            'module' => 'Core.Settings',
            'name' => 'Comments',
            'menu_id' =>'core.settings.comments',
            'route' => 'backend.settings.comments',
            'parent' => AdminMenu::where('menu_id', 'core.settings')->value('id'),
        ]);

    }
}
