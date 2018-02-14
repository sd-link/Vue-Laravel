<?php

namespace App\Models\Core\Settings;

use Illuminate\Database\Eloquent\Model;

class AdminMenu extends Model
{
    protected $table = 'admin_menu';
    protected $fillable = ['name', 'icon', 'visible', 'meta'];

    public function scopeParents($query)
    {
        return $query->where('parent', '=', null);
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent', 'id')->orderBy('id', 'asc');
    }

    static public function set($module, $name)
    {
        $item = AdminMenu::where('module', $module)->where('name', $name)->first();
        if($item) {
            $item->module = $module;
            $item->name = $name;
            $item->menu_id = 'menu_id';
            $item->icon = 'icon';
            $item->order = 22;
            $item->save();
        } else {
            $item = new AdminMenu();
            $item->module = $module;
            $item->name = $name;
            $item->route = 'backend.content.index';
            $item->menu_id = 'menu_id';
            $item->icon = 'icon';
            $item->order = 22;
            $item->save();
        }
    }

    static public function setSubMenuItem($parentId, $name, $module, $route, $menu_id) {
        $item = AdminMenu::where('parent', $parentId)->where('name', $name)->first();
        if($item) {
            $item->module = $module;
            $item->name = $name;
            $item->route = $route;
            $item->menu_id = $menu_id;
            $item->icon = 'icon';
            $item->parent = $parentId;
            $item->save();
        } else {
            $item = new AdminMenu();
            $item->module = $module;
            $item->name = $name;
            $item->route = $route;
            $item->menu_id = $menu_id;
            $item->icon = 'icon';
            $item->parent = $parentId;
            $item->save();
        }
    }

    static public function setContentMenuItem($module, $name, $taxonomies)
    {
        $item = AdminMenu::where('module', $module)->where('name', $name)->first();

        if($item) {
            $item->module = $module;
            $item->name = $name;
            $item->menu_id = 'menu_id';
            $item->route = 'backend.content.index';
            $item->icon = 'icon';
            $item->update();
        } else {
            $item = new AdminMenu();
            $item->module = $module;
            $item->name = $name;
            $item->route = 'backend.content.index';
            $item->menu_id = 'menu_id';
            $item->icon = 'icon';
            $lastContentItem = AdminMenu::where('module', 'Core.Content')->oldest()->first();
            if($lastContentItem)
                $item->order = $lastContent->order + 1;
            else {
                $item->order = 10 + 1;
            }
            $item->save();
        }

        if(count($taxonomies)) {
            AdminMenu::setSubMenuItem($item->id, 'All', $module, 'backend.content.index', 'core.content.all');

            foreach ($taxonomies as $key => $taxonomy) {
                AdminMenu::setSubMenuItem($item->id, $taxonomy->name, $module, 'backend.content.taxonomy', 'core.content.taxonomy');
            }
        }
    }

    static public function getModule($route)
    {
        $module = AdminMenu::where('route', $route)->first();
        if($module)
            return $module->module;
        else
            return null;
    }

    static public function getSubMenu($module)
    {
        $sub_menu = AdminMenu::where('module', $module)->where('parent', '!=', null)->get();

        return $sub_menu;
    }

    public function getRolesAttribute()
    {
        if(isset($this->meta)) {
            $meta = json_decode($this->meta);
            if(isset($meta->roles)) {
                // dd($this->meta->roles);
                return $meta->roles;
            }
            else
                return null;
        } else
            return null;
    }
}
