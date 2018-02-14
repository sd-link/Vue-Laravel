<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

\Theme::set('Larapress_2017_Theme');

Route::group(['middleware'=>'setTheme:Larapress_2017_Theme'], function () {
    Route::get('/home', 'HomeController@index');
});
// Auth::routes();

Route::group(['namespace' => 'Frontend\Core', 'middleware'=>'setTheme:Larapress_2017_Theme'], function () {

    Route::group(['namespace' => 'Blog', 'prefix' => 'blog'], function () {
        Route::get('/', ['as' => 'frontend.blog.index', 'uses' => 'PostController@index']);
        Route::get('{post}', ['as' => 'frontend.blog.show', 'uses' => 'PostController@show']);
        Route::post('comment', ['as' => 'frontend.blog.comment', 'uses' => 'PostController@comment']);
        Route::get('category/{category}', ['as' => 'frontend.blog.category', 'uses' => 'PostController@category']);
        Route::get('author/{author}', ['as' => 'frontend.blog.author', 'uses' => 'PostController@author']);
    });

    Route::group(['namespace' => 'Content', 'prefix' => 'page'], function () {
        Route::get('{page}', ['as' => 'frontend.pages.show', 'uses' => 'ContentController@show']);
    });

    // Route::group(['namespace' => 'Pages'], function () {
    //     Route::get('{page}', ['as' => 'frontend.pages.show', 'uses' => 'PageController@show']);
    // });

});

Route::get('/backend/categories', function() {
  return File::get(public_path() . '/content.html');
});

Route::group(['middleware'=>'setTheme:Admin_Theme'], function () {
    // Authentication Routes...
    Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
    Route::post('login', 'Auth\LoginController@login');
    Route::post('logout', 'Auth\LoginController@logout')->name('logout');

    // Registration Routes...
    // Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
    // Route::post('register', 'Auth\RegisterController@register');

    // Password Reset Routes...
    Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
    Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
    Route::post('password/reset', 'Auth\ResetPasswordController@reset');




    Route::group(['prefix' => 'backend', 'namespace' => 'Backend', 'middleware' => ['role:admin|editor|author']], function () {
    // Route::group(['prefix' => 'backend', 'namespace' => 'Backend'], function () {

        // Dashboard
        Route::group(['namespace' => 'Core'], function () {
            Route::get('dashboard', ['as' => 'backend.dashboard', 'uses' => 'BackendController@dashboard']);
        });

        // Projects
        Route::group(['namespace' => 'Core\Todos', 'prefix' => 'projects'], function () {
            Route::get('{id}/todos', ['as' => 'backend.project.todos', 'uses' => 'ProjectController@todos']);
            Route::get('create', ['as' => 'backend.project.create', 'uses' => 'ProjectController@create']);
            Route::get('edit/{id}', ['as' => 'backend.project.edit', 'uses' => 'ProjectController@edit']);
            Route::post('save', ['as' => 'backend.project.save', 'uses' => 'ProjectController@save']);
            Route::post('delete-block', ['as' => 'backend.project.delete_todo', 'uses' => 'ProjectController@deleteBlock']);
        });

        // Content Types
        Route::group(['namespace' => 'Core\Content\Types', 'prefix' => 'content-types'], function () {
            Route::get('/', ['as' => 'backend.content.types.index', 'uses' => 'ContentTypeController@index']);
            Route::get('{id}/taxonomies', ['as' => 'backend.content.types.taxonomies', 'uses' => 'ContentTypeController@taxonomies']);
            Route::get('create', ['as' => 'backend.content.types.create', 'uses' => 'ContentTypeController@create']);
            Route::get('edit/{id}', ['as' => 'backend.content.types.edit', 'uses' => 'ContentTypeController@edit']);
            Route::post('save', ['as' => 'backend.content.types.save', 'uses' => 'ContentTypeController@save']);
        });

        // Content Templates
        Route::group(['namespace' => 'Core\Content\Templates', 'prefix' => 'content-templates'], function () {
            Route::get('/', ['as' => 'backend.content.templates.index', 'uses' => 'TemplateController@index']);
            Route::get('{id}/blocks', ['as' => 'backend.content.templates.blocks', 'uses' => 'TemplateController@blocks']);
            Route::get('create', ['as' => 'backend.content.templates.create', 'uses' => 'TemplateController@create']);
            Route::get('edit/{id}', ['as' => 'backend.content.templates.edit', 'uses' => 'TemplateController@edit']);
            Route::post('save', ['as' => 'backend.content.templates.save', 'uses' => 'TemplateController@save']);
            Route::post('save-settings', ['as' => 'backend.content.templates.settings.save', 'uses' => 'TemplateController@saveSettings']);
        });

        // Content
        Route::group(['namespace' => 'Core\Content', 'prefix' => 'content'], function () {

            // content index page
            Route::get('{type}', ['as' => 'backend.content.index', 'uses' => 'ContentController@index']);

            // get content posts for current content type
            Route::get('{contentTypeId}/get', ['as' => 'backend.content.get.posts', 'uses' => 'ContentController@getPosts']);

            // content editor

            // return default template for content type
            Route::get('{type}/get/default-template', ['as' => 'backend.content.get.template.default', 'uses' => 'ContentController@getDefaultTemplate']);
            Route::get('{type}/get/template/{templateId}', ['as' => 'backend.content.get.template', 'uses' => 'ContentController@getTemplate']);

            // get existing blocks for content, these blocks build up the content
            Route::get('{id}/getcontent', ['as' => 'backend.content.get.content', 'uses' => 'ContentController@getBlocks']);

            // create, edit, save, delete content
            Route::get('{type}/create', ['as' => 'backend.content.create', 'uses' => 'ContentController@create']);
            Route::get('{type}/edit/{id}', ['as' => 'backend.content.edit', 'uses' => 'ContentController@edit']);
            Route::post('save', ['as' => 'backend.content.save', 'uses' => 'ContentController@save']);
            Route::post('{type}/delete', ['as' => 'backend.content.delete', 'uses' => 'ContentController@delete']);

            // save settings for index and edit pages (settings like user prefernces for index and editor page)
            Route::post('settings/save', ['as' => 'backend.content.settings.save', 'uses' => 'ContentController@saveSettings']);

            // taxonomies
            Route::get('{type}/taxonomy/{slug}', ['as' => 'backend.content.taxonomy', 'uses' => 'TaxonomyController@taxonomy']);
            Route::get('{type}/taxonomy/{slug}/terms', ['as' => 'backend.content.taxonomy.terms', 'uses' => 'TaxonomyController@getTerms']);
            Route::post('taxonomy/term/save', ['as' => 'backend.content.taxonomy.term.save', 'uses' => 'TaxonomyController@saveTerm']);
            Route::post('taxonomy/term/delete', ['as' => 'backend.content.taxonomy.term.delete', 'uses' => 'TaxonomyController@deleteTerm']);

            // featured image
            Route::post('set-featured-image', ['as' => 'backend.content.set.featuredimage', 'uses' => 'ContentController@setFeaturedImage']);
            Route::post('remove-featured-image', ['as' => 'backend.content.remove.featuredimage', 'uses' => 'ContentController@removeFeaturedImage']);
        });

        // Users, Roles, Permissions
        Route::group(['namespace' => 'Core\Users'], function () {
            // Users
            Route::group(['prefix' => 'users'], function () {
                Route::get('/', ['as' => 'backend.users.index', 'uses' => 'UserController@index']);
                Route::post('create', ['as' => 'backend.users.create', 'uses' => 'UserController@create']);
                Route::get('edit/{id}', ['as' => 'backend.users.edit', 'uses' => 'UserController@edit']);
                Route::get('crudpost', ['as' => 'backend.users.crudpost', 'uses' => 'UserController@getCrudPostUsers']);
                Route::get('getallroles', ['as' => 'backend.users.getallroles', 'uses' => 'UserController@getAllRoles']);
                Route::post('update', ['as' => 'backend.users.update', 'uses' => 'UserController@update']);
                Route::post('delete', ['as' => 'backend.users.delete', 'uses' => 'UserController@delete']);
            });

            // Roles
            Route::group(['prefix' => 'roles'], function () {
                Route::get('/', ['as' => 'backend.roles.index', 'uses' => 'RoleController@index']);
                Route::post('create',  ['as' => 'backend.roles.create', 'uses' => 'RoleController@create']);
                Route::get('edit/{id}', ['as' => 'backend.roles.edit', 'uses' => 'RoleController@edit']);
                Route::get('get/{id}', ['as' => 'backend.roles.get', 'uses' => 'RoleController@get']);
                Route::post('store',  ['as' => 'backend.roles.store', 'uses' => 'RoleController@store']);
                Route::post('update', ['as' => 'backend.roles.update', 'uses' => 'RoleController@update']);
                Route::post('delete', ['as' => 'backend.roles.delete', 'uses' => 'RoleController@delete']);
            });

            // Permissions
            Route::group(['prefix' => 'permissions'], function () {
                Route::get('/', ['as' => 'backend.permissions.index', 'uses' => 'PermissionController@index']);
                Route::post('create',  ['as' => 'backend.permissions.create', 'uses' => 'PermissionController@create']);
                Route::get('edit/{id}', ['as' => 'backend.permissions.edit', 'uses' => 'PermissionController@edit']);
                Route::post('store',  ['as' => 'backend.permissions.store', 'uses' => 'PermissionController@store']);
                Route::post('update', ['as' => 'backend.permissions.update', 'uses' => 'PermissionController@update']);
                Route::post('delete', ['as' => 'backend.permissions.delete', 'uses' => 'PermissionController@delete']);
            });
        });

        // Forms
        Route::group(['namespace' => 'Core\Forms', 'prefix' => 'forms'], function () {
            Route::get('all', ['as' => 'backend.forms.index', 'uses' => 'FormController@index']);
            Route::get('create', ['as' => 'backend.forms.create', 'uses' => 'FormController@create']);
            Route::get('edit/{id}', ['as' => 'backend.forms.edit', 'uses' => 'FormController@edit']);
            Route::post('store', ['as' => 'backend.forms.store', 'uses' => 'FormController@store']);
            Route::post('update', ['as' => 'backend.forms.update', 'uses' => 'FormController@update']);
            Route::post('quickupdate', ['as' => 'backend.forms.quickupdate', 'uses' => 'FormController@quickUpdate']);
            Route::get('settings', ['as' => 'backend.forms.settings', 'uses' => 'FormController@settings']);
            Route::post('delete', ['as' => 'backend.forms.delete', 'uses' => 'FormController@delete']);
        });

        // Comments
        Route::group(['namespace' => 'Core\Comments', 'prefix' => 'comments'], function () {
            Route::get('/', ['as' => 'backend.comments.index', 'uses' => 'CommentController@index']);
            Route::post('status', ['as' => 'backend.comments.status', 'uses' => 'CommentController@status']);
            Route::post('delete', ['as' => 'backend.comments.delete', 'uses' => 'CommentController@delete']);
        });

        // Design
        Route::group(['namespace' => 'Core\Design', 'prefix' => 'design'], function () {
            Route::get('customize', ['as' => 'backend.design.customize', 'uses' => 'DesignController@customize']);
            Route::get('themes', ['as' => 'backend.design.themes', 'uses' => 'DesignController@themes']);
            Route::get('menus', ['as' => 'backend.design.menus', 'uses' => 'DesignController@menus']);
            Route::get('widgets', ['as' => 'backend.design.widgets', 'uses' => 'DesignController@widgets']);
        });

        Route::group(['namespace' => 'Core\Extensions', 'prefix' => 'extensions'], function () {
            Route::get('installed', ['as' => 'backend.extensions.installed', 'uses' => 'ExtensionsController@installed']);
            Route::get('new', ['as' => 'backend.extensions.new', 'uses' => 'ExtensionsController@new']);
        });

        // Settings
        Route::group(['namespace' => 'Core\Settings', 'prefix' => 'settings'], function () {
            Route::get('website', ['as' => 'backend.settings.website', 'uses' => 'WebsiteController@index']);
            Route::post('website', ['as' => 'backend.settings.website.save', 'uses' => 'WebsiteController@save']);
            Route::get('admin', ['as' => 'backend.settings.admin', 'uses' => 'AdminMenuController@index']);
            Route::post('admin/theme/save', ['as' => 'backend.settings.admin.theme.save', 'uses' => 'AdminMenuController@layoutThemeSave']);
            Route::post('admin/menu/item/save', ['as' => 'backend.settings.admin.menu.item.save', 'uses' => 'AdminMenuController@itemSave']);
            Route::post('admin/menu/item/order', ['as' => 'backend.settings.admin.menu.item.order', 'uses' => 'AdminMenuController@itemOrder']);
            Route::post('admin/customcss/', ['as' => 'backend.settings.admin.customcss.save', 'uses' => 'AdminMenuController@customcssSave']);
            Route::post('admin/content/', ['as' => 'backend.settings.admin.content.save', 'uses' => 'AdminMenuController@contentSave']);
            Route::post('admin/login/', ['as' => 'backend.settings.admin.login.save', 'uses' => 'AdminMenuController@loginSave']);
            Route::get('comments', ['as' => 'backend.settings.comments', 'uses' => 'CommentsController@index']);
            Route::post('comments', ['as' => 'backend.settings.comments.save', 'uses' => 'CommentsController@save']);
            Route::get('members', ['as' => 'backend.settings.members', 'uses' => 'MembersController@index']);
            Route::post('members', ['as' => 'backend.settings.members.save', 'uses' => 'MembersController@save']);
        });




        // Media
        Route::group(['namespace' => 'Core\Media', 'prefix' => 'media'], function () {
            Route::get('/', ['as' => 'backend.media.index', 'uses' => 'MediaController@index']);
            Route::get('latest', ['as' => 'backend.media.latest', 'uses' => 'MediaController@images']);
            Route::post('upload', ['as' => 'backend.media.upload', 'uses' => 'MediaController@upload']);
            Route::get('images', ['as' => 'backend.media.images.index', 'uses' => 'ImageController@index']);

            // Galleries
            Route::group(['prefix' => 'galleries'], function () {
                Route::get('/', ['as' => 'backend.media.galleries.index', 'uses' => 'GalleryController@index']);
                Route::get('get', ['as' => 'backend.media.galleries.get', 'uses' => 'GalleryController@get']);
                Route::get('all', ['as' => 'backend.media.galleries.all', 'uses' => 'GalleryController@all']);
                Route::get('session/images', ['as' => 'backend.media.galleries.session.images', 'uses' => 'GalleryController@sessionImages']);
                Route::get('{id}/images', ['as' => 'backend.media.galleries.images', 'uses' => 'GalleryController@images']);
                Route::get('create', ['as' => 'backend.media.galleries.create', 'uses' => 'GalleryController@create']);
                Route::get('edit/{id}', ['as' => 'backend.media.galleries.edit', 'uses' => 'GalleryController@edit']);
                Route::post('save', ['as' => 'backend.media.galleries.save', 'uses' => 'GalleryController@save']);
                Route::post('settings/save', ['as' => 'backend.media.galleries.settings.save', 'uses' => 'GalleryController@saveSettings']);
                Route::post('upload', ['as' => 'backend.media.galleries.upload', 'uses' => 'GalleryController@upload']);
                Route::post('delete', ['as' => 'backend.media.galleries.delete', 'uses' => 'GalleryController@delete']);
                Route::post('order', ['as' => 'backend.media.galleries.order', 'uses' => 'GalleryController@order']);
            });

            // Images
            Route::group(['prefix' => 'images'], function () {
                Route::get('/', ['as' => 'backend.media.images.index', 'uses' => 'ImageController@index']);
                Route::get('create', ['as' => 'backend.media.images.create', 'uses' => 'ImageController@create']);
                Route::post('get', ['as' => 'backend.media.images.get', 'uses' => 'ImageController@get']);
                Route::post('update', ['as' => 'backend.media.images.update', 'uses' => 'ImageController@update']);
                Route::post('upload', ['as' => 'backend.media.images.upload', 'uses' => 'ImageController@upload']);
                Route::post('delete', ['as' => 'backend.media.images.delete', 'uses' => 'ImageController@delete']);
            });

            // Tags
            Route::group(['prefix' => 'tags', 'middleware' => ['role:admin|editor']], function () {
                Route::get('/', ['as' => 'backend.media.tags.index', 'uses' => 'MediaController@tags']);

                Route::group(['prefix' => 'gallery'], function () {
                    Route::get('/', ['as' => 'backend.media.gallery.tags.index', 'uses' => 'GalleryTagController@index']);
                    Route::get('all',  ['as' => 'backend.media.gallery.tags.all', 'uses' => 'GalleryTagController@all']);
                    Route::post('create',  ['as' => 'backend.media.gallery.tags.create', 'uses' => 'GalleryTagController@create']);
                    Route::post('update',  ['as' => 'backend.media.gallery.tags.update', 'uses' => 'GalleryTagController@update']);
                    Route::post('delete',  ['as' => 'backend.media.gallery.tags.delete', 'uses' => 'GalleryTagController@delete']);
                    Route::get('get/{id}', ['as' => 'backend.media.gallery.tags.get', 'uses' => 'GalleryTagController@get']);
                });
                Route::group(['prefix' => 'image'], function () {
                    Route::get('/', ['as' => 'backend.media.image.tags.index', 'uses' => 'ImageTagController@index']);
                    Route::get('all',  ['as' => 'backend.media.image.tags.all', 'uses' => 'ImageTagController@all']);
                    Route::post('create',  ['as' => 'backend.media.image.tags.create', 'uses' => 'ImageTagController@create']);
                    Route::post('update',  ['as' => 'backend.media.image.tags.update', 'uses' => 'ImageTagController@update']);
                    Route::post('delete',  ['as' => 'backend.media.image.tags.delete', 'uses' => 'ImageTagController@delete']);
                    Route::get('get/{id}', ['as' => 'backend.media.image.tags.get', 'uses' => 'ImageTagController@get']);
                });
            });
        });

    }); // backend
});
