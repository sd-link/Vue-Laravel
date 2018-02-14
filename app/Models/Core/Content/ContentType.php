<?php

namespace App\Models\Core\Content;

use Illuminate\Database\Eloquent\Model;

use App\Models\Core\Settings\HasSettings;
use App\Models\Core\Taxonomies\Taxonomy;
use App\Models\Core\Settings\AdminMenu;

class ContentType extends Model
{
    use HasSettings;

    public function taxonomies()
    {
        return $this->hasMany(Taxonomy::class, 'content_type_id', 'id');
    }

    public function templates()
    {
        return $this->hasMany(Template::class, 'content_type_id', 'id');
    }

    public function getTaxonomy($name)
    {
        return $this->taxonomies()->where('name', $name)->where('content_type_id', $this->id)->first();
    }

    public function taxonomyExists($name)
    {
        return $this->taxonomies()->where('name', $name)->exists();
    }

    static public function createOrUpdateContentMenuItem($module, $name, $taxonomies)
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
            $lastContentItem = AdminMenu::where('module', 'Core.Content.'.$name)->oldest()->first();
            if($lastContentItem)
                $item->order = $lastContentItem->order + 1;
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
}
