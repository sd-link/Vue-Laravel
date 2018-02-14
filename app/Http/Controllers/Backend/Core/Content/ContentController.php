<?php

namespace App\Http\Controllers\Backend\Core\Content;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Core\Content\ContentType;
use App\Models\Core\Content\Template;
use App\Models\Core\Content\TemplateBlock;
use App\Models\Core\Content\Content;
use App\Models\Core\Content\ContentBlock;
use App\Models\Core\Settings\SiteSetting;

use App\Models\Core\Settings\Setting;
use App\Models\Core\Taxonomies\Taxonomy;

use DB;
use Session;
use Auth;
use Timezone;
use Carbon\Carbon;
use Jenssegers\Agent\Agent;

class ContentController extends Controller
{

    public function getPosts(Request $request)
    {
        $search = $request->search;
        $filter = $request->filter;
        $status = $request->status;
        $page = $request->page;

        if(isset($request->per_page))
            $per_page = $request->per_page;
        else
            $per_page = 12;

        $sort = $request->sort;
        $contentTypeId = $request->contentTypeId;

        $append = array();

        // $content = Content::with('author')->with('featuredimage');
        // $type = ContentType::where('slug', $contentType)->first();
        $content = Content::with('terms')->with('author')->with('featuredimage')->where('content_type_id', $contentTypeId);

        if($sort == 'latest')
            $content = $content->latest();
        else
            $content = $content->oldest();

        if($status) {
            switch ($request->status) {
                case 'published':
                    $content = $content->published();
                break;

                case 'drafts':
                    $content = $content->drafts();
                break;

                case 'scheduled':
                    $content = $content->scheduled();
                break;

            }
            $append += array('status' => $status);
        }

        if($search) {
            switch ($filter) {
                case 'username':
                    $content->whereHas('author', function($query) use($search) {
                        $query->where('firstname', 'LIKE', '%'. $search . '%');
                    });
                break;

                case 'title':
                    $content->where('title', 'LIKE', '%'. $search . '%');
                break;

                default:
                    # code...
                break;
            }
            $append += array('search' => $search);
            $append += array('filter' => $filter);
        }

        $content = $content->paginate($per_page);
        // $content->appends($append);

        $allCount = Content::where('content_type_id', $contentTypeId)->count();
        $returnedCount = $content->total();
        $publishedCount = Content::where('content_type_id', $contentTypeId)->where('status', Content::PUBLISH)->count();
        $draftCount = Content::where('content_type_id', $contentTypeId)->where('status', Content::DRAFT)->count();
        $scheduledCount = Content::where('content_type_id', $contentTypeId)->where('status', Content::SCHEDULE)->count();

        $counts = (object)[];
        $counts->allCount = $allCount;
        $counts->returnedCount = $returnedCount;
        $counts->publishedCount = $publishedCount;
        $counts->draftCount = $draftCount;
        $counts->scheduledCount = $scheduledCount;

        $data = (object)[];
        $data->content = $content;
        $data->counts = $counts;

        return response()->json($data);
    }

    public function index($type)
    {
        // $content = Content::with('taxonomies')->where('id', 1)->first();

        // $type = ContentType::where('name', 'content')->first();

        // $type = ContentType::where('id', 2)->first();
        //
        //
        // $content = Content::with('terms')->where('content_type_id', $type->id)->get();
        //
        // // $content->addTaxonomy('category');
        //
        // dump($type->taxonomies);
        // dump($content);


        // $search = $request->search;
        $filter = '$request->filter';
        // $status = $request->status;
        // $page = $request->page;
        $append = array();
        $content = Content::with('terms')->whereHas('type', function($query) use($type) {
            $query->where('name', $type);
        });
        // $content = Content::with('terms')->oldest();

        // dd($content);

        $status = 'published';
        $search = null;

        if($status) {
            switch ($status) {
                case 'published':
                    $content = $content->published();
                break;

                case 'drafts':
                    $content = $content->drafts();
                break;

                case 'scheduled':
                    $content = $content->scheduled();
                break;
            }
            $append += array('status' => $status);
        }

        if($search) {
            switch ($filter) {

                case 'title':
                    $content->where('title', 'LIKE', '%'. $search . '%');
                break;

                case 'username':
                    $content->whereHas('author', function($query) use($search) {
                        $query->where('username', 'LIKE', '%'. $search . '%');
                    });
                break;

                default:
                    # code...
                break;
            }
            $append += array('search' => $search);
            $append += array('filter' => $filter);
        }

        $content = $content->paginate(12);

        // $content->appends($append);

        if($content->lastPage() && !$content->count()) {
            return redirect($content->previousPageUrl());
        }

        $allCount = Content::count();
        $publishedCount = Content::where('status', Content::PUBLISH)->count();
        $draftCount = Content::where('status', Content::DRAFT)->count();
        $scheduledCount = Content::where('status', Content::SCHEDULE)->count();
        $returnedCount = $content->total();

        $content->allCount = $allCount;
        $content->returnedCount = $returnedCount;
        $content->publishedCount = $publishedCount;
        $content->draftCount = $draftCount;
        $content->scheduledCount = $scheduledCount;

        $type = ContentType::with('settings')->where('name', $type)->first();
        $settings = $type->getSettingsFilteredForVue();

        return view('core.content.index', compact('content', 'type', 'settings', 'page', 'search', 'filter'));
    }

    public function getDefaultTemplate($type)
    {
        $type = ContentType::where('slug', $type)->first();
        $template = Template::where('content_type_id', $type->id)->where('default', 1)->first();
        $blocks = [];

        if($template) {
            $blocks = TemplateBlock::with('settings')->where('template_id', $template->id)->get();
        }

        return response()->json([
            'status' => 'success',
            'blocks' => $blocks
        ], 201);
    }

    public function getTemplate($type, $templateId)
    {
        $blocks = TemplateBlock::with('settings')->where('template_id', $templateId)->get();

        return response()->json([
            'status' => 'success',
            'blocks' => $blocks
        ], 201);
    }

    public function create($type)
    {
        $contentType = ContentType::where('slug', $type)->first();
        $editorSettings = $contentType->getSettingsFilteredForVue();


        return view('core.content.create', compact('contentType', 'editorSettings'));
    }

    public function edit($type, $contentId)
    {
        $contentType = ContentType::where('name', $type)->first();
        $content = Content::with('blocks')->where('id', $contentId)->first();
        $editorSettings = $contentType->getSettingsFilteredForVue();

        return view('core.content.edit', compact('contentType', 'content', 'editorSettings'));
    }

    public function saveSettings(Request $request) {
        // foreach ($request->settings as $key => $setting) {
        //     dump($key);
        //     SiteSetting::set('Editor.Content', $key, $setting['value'], $setting['type']);
        // }

        $contentType = ContentType::where('id', $request->contentTypeId)->first();

        // Save Content Type Settings
        foreach ($request->settings as $key => $setting) {
            $contentType->setSetting($key, $setting['value'], $setting['type']);
        }

        return response()->json([
            'status' => 'success',
        ], 201);
    }

    public function getBlocks($id)
    {
        $content = Content::with('blocks.settings')->with('featuredimage')->where('id', $id)->first();

        foreach ($content->blocks as $key => $block) {
            $title = $block->settings;
            foreach ($block->settings as $key => $setting) {
                if($setting['key'] == 'order') {
                    $block->order = (int)$setting['value'];
                    break;
                }
            }
        }

        // sort the blocks
        $content->blocks = $content->blocks->sortBy('order');

        // reset keys
        $blocks = [];
        foreach ($content->blocks as $key => $block) {
            array_push($blocks, $block);
        }

        return response()->json([
            'status' => 'success',
            'blocks' => $blocks,
            'featuredImage' => $content->featuredimage,
        ], 201);
    }

    public function save(Request $request)
    {
        // delete removed blocks
        foreach ($request->removedItems as $key => $itemId) {
            $block = ContentBlock::where('unique_id', $itemId)->first();

            if($block) {
                $block->delete();
            }
        }

        // dump('creating content');

        if($request->id > 0)
            $content = Content::where('id', $request->id)->first();
        else {
            $content = new Content();
            $content->content_type_id = $request->contentTypeId;
        }

        // dump('content created');

        $content->title = $request->title;
        $content->status = $request->status;
        // dump('before accessing published_at');
        $content->published_at = $request->publish_at;
        $content->user_id = Auth::user()->id;
        $content->slug = $content->title;
        $content->save();

        foreach ($request->blocksData as $key => $blockData) {
            $blockData = (object) $blockData;
            if(isset($blockData->templateBlockId)) {
                $this->createOrUpdateTBlock($content->id, $blockData);
            }
            else {
                $this->createOrUpdateBlock($content->id, $blockData);
            }
        }

        $content = Content::with('blocks')->where('id', $content->id)->first();

        if($content) {
            return response()->json([
                'status' => 'success',
                'content' => $content
            ], 201);
        }
    }

    protected function createOrUpdateBlock($contentId, $blockData, $parentId = null)
    {
        $blockData = (object) $blockData;

        $block = ContentBlock::set($contentId, $blockData, $parentId);
        foreach ($block->getSettingsNoDefaults() as $baseKey => $value) {
            $found = false;
            foreach ($blockData->settings as $saveKey => $value) {
                if($baseKey == $saveKey) {
                    $found = true;
                    break;
                }
            }

            if(!$found) {
                $block->removeSetting($baseKey);
            }
        }

        if($block) {
            foreach ($blockData->settings as $key => $setting) {
                $block->setSetting($key, $setting['value'], $setting['type']);
            }

            if(isset($blockData->subItems))
                for ($i=0; $i < count($blockData->subItems); $i++) {
                    $this->createOrUpdateBlock($contentId, $blockData->subItems[$i], $block->unique_id);
                }
        }
    }

    protected function createOrUpdateTBlock($contentId, $blockData, $parentId = null)
    {
        $blockData = (object) $blockData;

        $templateBlock = TemplateBlock::where('id', $blockData->templateBlockId)->first();
        if($templateBlock) {
            $block = ContentBlock::set($contentId, $blockData, $parentId);
            $settings = $templateBlock->settings;
            $settingIds = array();
            foreach ($templateBlock->settings as $key => $value) {
                array_push($settingIds, $value['pivot']['setting_id']);
            }

            $block->settings()->sync($settingIds);

            if(isset($blockData->subItems))
                for ($i=0; $i < count($blockData->subItems); $i++) {
                    $newBlock = $blockData->subItems[$i];
                    if(isset($newBlock['templateBlockId'])) {
                        $this->createOrUpdateTBlock($contentId, $blockData->subItems[$i], $block->unique_id);
                    }
                    else {
                        $this->createOrUpdateBlock($contentId, $blockData->subItems[$i], $block->unique_id);
                    }
                }
        }
    }


    /**
     * Adds featured image.
     *
     * @return json response
     */
    public function setFeaturedImage(Request $request)
    {
        if($request->id) {
            $content = Content::find($request->id);
            if($content) {
                $content->featured_image_id = $request->featuredImageId;
                $content->save();
                return response()->json([
                    'status' => 'success'
                ], 201);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'This content has been deleted.',
                ], 404);
            }
        } else {
            Session::put('featured_image_id', $request->featuredImageId);
            return response()->json([
                'status' => 'success',
            ], 201);
        }
    }

    /**
     * Removes featured image.
     *
     * @return json response
     */
    public function removeFeaturedImage(Request $request)
    {
        if($request->id) {
            $content = Content::find($request->id);
            if($content) {
                $content->featured_image_id = null;
                $content->save();
                return response()->json([
                    'status' => 'success'
                ], 201);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Could not remove featured image. Does the content still exist?'
                ], 404);
            }
        } else {
            Session::pull('featured_image_id');
            return response()->json([
                'status' => 'success'
            ], 201);
        }
    }

}
