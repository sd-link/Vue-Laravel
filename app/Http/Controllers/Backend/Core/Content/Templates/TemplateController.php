<?php

namespace App\Http\Controllers\Backend\Core\Content\Templates;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Core\Content\ContentType;
use App\Models\Core\Content\Template;
use App\Models\Core\Content\TemplateBlock;
use App\Models\Core\Settings\SiteSetting;

use DB;
// use Content;
use Session;
use Auth;
use Timezone;
use Carbon\Carbon;
use Jenssegers\Agent\Agent;

class TemplateController extends Controller
{
    public function index(Request $request)
    {
        $templates = Template::latest()->paginate(10);

        return view('core.content.templates.index', compact('templates'));
    }

    public function blocks($id)
    {
        $blocks = TemplateBlock::with('settings')->where('template_id', $id)->get();

        return response()->json([
            'status' => 'success',
            'blocks' => $blocks
        ], 201);
    }

    public function create()
    {
        $contentTypes = ContentType::latest()->get();
        $editorSettings = SiteSetting::getSection('Editor.Templates', true);
        return view('core.content.templates.create', compact('contentTypes', 'editorSettings'));
    }


    public function edit($id)
    {
        $contentTypes = ContentType::latest()->get();
        $template = Template::where('id', $id)->first();
        $blocks = $template->blocks();
        $editorSettings = SiteSetting::getSection('Editor.Templates', true);

        return view('core.content.templates.edit', compact('contentTypes', 'template', 'blocks', 'editorSettings'));
    }

    public function saveSettings(Request $request) {
        foreach ($request->except('_token') as $key => $setting) {
            SiteSetting::set('Editor.Templates', $key, $setting['value'], $setting['type']);
        }
    }

    public function save(Request $request)
    {
        // delete removed blocks
        foreach ($request->removedItems as $key => $itemId) {
            $templateBlock = TemplateBlock::where('unique_id', $itemId)->first();
            if($templateBlock) {
                $templateBlock->delete();
            }
        }

        if($request->id > 0)
            $template = Template::where('id', $request->id)->first();
        else
            $template = new Template();

        $template->name = $request->name;

        if($request->defaultTemplate && $request->contentTypeId > 0) {
            $template->content_type_id = $request->contentTypeId;
            $template->default = $request->defaultTemplate;
        }

        $template->save();

        $blocksData = $request->blocksData;

        for ($i=0; $i < count($blocksData); $i++) {
            $this->createOrUpdateBlock($template->id, $blocksData[$i]);
        }

        $template = Template::where('id', $template->id)->first();

        if($template) {
            return response()->json([
                'status' => 'success',
                'template' => $template,
            ], 201);
        }
    }

    protected function createOrUpdateBlock($template_id, $blockData, $parentId = null)
    {
        $blockData = (object) $blockData;
        $tBlock = TemplateBlock::set($template_id, $blockData, $parentId);

        foreach ($blockData->settings as $key => $setting) {
            // dump($key . ': ' . $setting['value'] . ' ' . $setting['type']);
            $tBlock->setSetting($key, $setting['value'], $setting['type']);
        }
        if(isset($blockData->subItems))
            for ($i=0; $i < count($blockData->subItems); $i++) {
                $this->createOrUpdateBlock($template_id, $blockData->subItems[$i], $tBlock->unique_id);
            }
    }
}
