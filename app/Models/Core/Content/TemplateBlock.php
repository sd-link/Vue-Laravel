<?php

namespace App\Models\Core\Content;

use Illuminate\Database\Eloquent\Model;
use App\Models\Core\Settings\HasSettings;

class TemplateBlock extends Model
{
    use HasSettings;

    protected $table = "template_blocks";

    public function template()
    {
        return $this->belongsTo(Template::class, 'content_template_id', 'id');
    }

    public function subBlocks()
    {
        return $this->hasMany(self::class, 'parent_id', 'unique_id');
    }

    public function contentBlocks()
    {
        return $this->hasMany(ContentBlock::class, 'tblock_id', 'id');
    }

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {
            // $model->settings()->delete();
            $model->settings()->sync([]);
            // $model->subBlocks()->delete();
            // dump('deleting: ' . $model->type);
            foreach ($model->contentBlocks as $key => $contentBlock) {
                // dump('deleting: ' . $contentBlock->type);
                $contentBlock->delete();
            }

            foreach ($model->subBlocks as $key => $subBlock) {
                $subBlock->delete();
                self::recursiveDelete($subBlock);
            }
        });
   }

    public static function recursiveDelete($subBlock) {
        // foreach ($subBlock->contentBlocks as $key => $contentBlock) {
        //     $contentBlock->delete();
        // }
        foreach ($subBlock->subBlocks as $key => $subBlock) {
            $subBlock->delete();
            self::recursiveDelete($subBlock);
        }
    }

    public function scopeParents($query)
    {
        return $query->where('parent_id', '=', null);
    }

    static public function set($template_id, $blockData, $parentId = null)
    {
        $block = TemplateBlock::where('unique_id', $blockData->uniqueId)->where('template_id', $template_id)->first();

        if($block) {
            $block->template_id = $template_id;
            $block->unique_id = $blockData->uniqueId;
            $block->parent_id = $parentId;
            $block->type = $blockData->type;
            if(isset($blockData->content)) {
                if(is_array($blockData->content))
                    $block->content = json_encode($blockData->content);
                else
                    $block->content = $blockData->content;
            }
            $block->order = $blockData->order;
            $block->update();
        } else {
            $block = new TemplateBlock();
            $block->template_id = $template_id;
            $block->unique_id = $blockData->uniqueId;
            $block->parent_id = $parentId;
            $block->type = $blockData->type;
            if(isset($blockData->content)) {
                if(is_array($blockData->content))
                    $block->content = json_encode($blockData->content);
                else
                    $block->content = $blockData->content;
            }
            $block->order = $blockData->order;
            $block->save();
        }

        return $block;
    }
}
