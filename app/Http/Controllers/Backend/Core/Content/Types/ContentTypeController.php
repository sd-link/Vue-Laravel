<?php

namespace App\Http\Controllers\Backend\Core\Content\Types;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Core\Content\ContentType;
use App\Models\Core\Content\ContentTypeBlock;
use App\Models\Core\Taxonomies\Taxonomy;
use App\Models\Core\Settings\AdminMenu;

use DB;
// use Content;
use Session;
use Auth;
use Timezone;
use Carbon\Carbon;
use Jenssegers\Agent\Agent;

class ContentTypeController extends Controller
{
    public function index(Request $request)
    {
        $contentType = ContentType::latest()->paginate(10);

        return view('core.settings.content_types.index', compact('contentType'));
    }

    public function getblocks($slug)
    {
        $contentType = ContentType::where('slug', $slug)->first();
        $contentType->blocks = $contentType->blocks()->orderBy('order')->get();

        foreach ($contentType->blocks as $key => $block) {
            $block->settings;
        }

        return response()->json([
            'status' => 'success',
            'blocks' => $contentType->blocks
        ], 201);
    }

    public function taxonomies($id)
    {
        $contentType = ContentType::where('id', $id)->first();
        $contentType->taxonomies = $contentType->taxonomies()->orderBy('order')->get();

        foreach ($contentType->taxonomies as $key => $taxonomy) {
            $taxonomy->settings;
        }

        return response()->json([
            'status' => 'success',
            'taxonomies' => $contentType->taxonomies
        ], 201);
    }

    public function create()
    {
        $mode = 'create';
        return view('core.settings.content_types.create');
    }


    public function edit($id)
    {
        $mode = 'edit';
        $contentType = ContentType::where('id', $id)->first();

        return view('core.settings.content_types.edit', compact('contentType'));
    }

    public function deleteBlock(Request $request)
    {
        $contentTypeBlock = ContentTypeBlock::where('id', $request->id)->first();

        $contentTypeBlock->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Block deleted'
        ], 201);
    }

    public function save(Request $request)
    {
        // $contentType = ContentType::where('id', $request->id)->first();
        if($request->id > 0)
            $contentType = ContentType::where('id', $request->id)->first();
        else {
            $contentType = new ContentType();
            $contentType->name = $request->typeData['name'];
            $contentType->name_singular = $request->typeData['name_singular'];
            $contentType->slug = lcfirst($request->typeData['name_singular']);
        }

        $contentType->save();

        // Save Content Type Settings
        foreach ($request->typeSettings as $key => $setting) {
            $contentType->setSetting($key, $setting['value'], $setting['type']);
        }

        $taxonomiesData = $request->taxonomiesData;
        $taxonomiesSettings = $request->taxonomiesSettings;
        // dump($taxonomiesData);
        for ($i=0; $i < count($taxonomiesData); $i++) {
            // dump('creating taxonomy: ' . $taxonomiesData[$i]);
            $this->createTaxonomy($contentType->id, $taxonomiesData[$i], $taxonomiesSettings[$i]);
        }

        $contentType = ContentType::where('id', $contentType->id)->first();
        $contentType->taxonomies = $contentType->taxonomies()->orderBy('order')->get();

        foreach ($contentType->taxonomies as $key => $taxonomy) {
            $taxonomy->settings;
        }

        $module = 'Core.Content.' . $contentType->name;
        $name = $contentType->name;

        ContentType::createOrUpdateContentMenuItem($module, $name, $contentType->taxonomies);

        if($contentType) {
            return response()->json([
                'status' => 'success',
                'contentType' => $contentType
            ], 201);
        }
    }

    protected function createTaxonomy($content_type_id, $taxonomyData, $taxonomySettings)
    {
        // dump($blockData);
        $taxonomy = Taxonomy::createOrUpdate($content_type_id, $taxonomyData);

        foreach ($taxonomySettings as $key => $setting) {
            // dump($key . ': ' . $setting['value'] . ' ' . $setting['type']);
            $taxonomy->setSetting($key, $setting['value'], $setting['type']);
        }
    }

}
