<?php

namespace App\Models\Core\Content;

use Illuminate\Database\Eloquent\Model;

use App\Models\Core\Settings\HasSettings;
use Auth;

class ContentBlock extends Model
{
    use HasSettings;

    protected $table = "content_blocks";

    public function content()
    {
        return $this->belongsTo(Content::class, 'content_id', 'id');
    }

    public function subBlocks()
    {
        return $this->hasMany(self::class, 'parent_id', 'unique_id');
    }

   public static function boot()
   {
       parent::boot();

       static::deleting(function ($model) {
           $model->settings()->delete();
           $model->settings()->sync([]);
           foreach ($model->subBlocks as $key => $subBlock) {
               $subBlock->delete();
               self::recursiveDelete($subBlock);
           }
       });
  }

   public static function recursiveDelete($subBlock) {
       foreach ($subBlock->subBlocks as $key => $model) {
           $model->delete();
           self::recursiveDelete($model);
       }
   }

    static public function shouldRender($device, $deviceVisibility)
    {
        foreach ($deviceVisibility as $key => $visibility) {
            if($device == $key)
                return $visibility;
        }
    }

    static public function set($content_id, $blockData, $parentId)
    {
        $block = ContentBlock::where('unique_id', $blockData->uniqueId)->where('content_id', $content_id)->first();

        if($block) {
            $block->content_id = $content_id;

            $block->order = $blockData->order;
            $block->title = $blockData->title;
            $block->unique_id = $blockData->uniqueId;
            $block->parent_id = $parentId;
            $block->user_id = Auth::user()->id;

            if(isset($blockData->content)) {
                if(is_array($blockData->content))
                    $block->content = json_encode($blockData->content);
                else
                    $block->content = $blockData->content;
            }

            if(isset($blockData->templateBlockId)) {
                $block->tblock_id = $blockData->templateBlockId;
            }

            $block->update();
        } else {
            $block = new ContentBlock();
            $block->content_id = $content_id;
            $block->type = $blockData->type;
            $block->order = $blockData->order;
            $block->title = $blockData->title;
            $block->unique_id = $blockData->uniqueId;
            $block->parent_id = $parentId;

            $block->user_id = Auth::user()->id;

            if(isset($blockData->content)) {
                if(is_array($blockData->content))
                    $block->content = json_encode($blockData->content);
                else
                    $block->content = $blockData->content;
            }

            if(isset($blockData->templateBlockId)) {
                $block->tblock_id = $blockData->templateBlockId;
            }

            $block->save();
        }

        return $block;
    }
}
