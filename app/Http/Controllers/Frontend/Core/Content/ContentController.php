<?php

namespace App\Http\Controllers\Frontend\Core\Content;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Core\Content\Content;
use App\Models\Core\Content\ContentBlock;
use App\Models\User;
use App\Models\Core\Comments\Comment;
use App\Models\Core\Comments\Subscriber;
use Setting;
use Session;

class ContentController extends Controller
{
    public function show($slug)
    {
        // $content = Content::with(array('blocks' => function ($query) {
        //                         $query->orderBy('order')->with('settings');
        //                     }))->where('slug', $slug)->first();
        // $blocks = array();

        $content = Content::with('featuredimage')->where('slug', $slug)->first();

        $rootBlocksIds = array();
        $allBlocks = array();

        $blocks = ContentBlock::with(array('settings'=>function($query) {
            $query->select('id','key', 'value', 'type');
        }))->where('content_id', $content->id)->orderBy('parent_id')->orderBy('order')->get();

        // dd(response()->json($blocks));

        // dd(json_encode($blocks));

        foreach ($blocks as $key => $block) {
            $block->subItems = array();
        }
        // Process blocks
        foreach ($blocks as $key => $block) {
            $allBlocks[$block->unique_id] = $block;
            if ($block->parent_id) {
                $parent = $allBlocks[$block->parent_id];
                $subs = $parent->subItems;
                array_push($subs, $block->unique_id);
                $parent->subItems = $subs;
            } else {
                $rootBlocksIds[$block->unique_id] = $block->unique_id;
            }
        }

        $rendered = view('content.show', compact('rootBlocksIds', 'allBlocks', 'content'));
        // $content->rendered = $rendered;
        // $content->save();
        return $rendered;
    }

}
