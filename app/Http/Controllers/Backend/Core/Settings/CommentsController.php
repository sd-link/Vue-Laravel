<?php

namespace App\Http\Controllers\Backend\Core\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Core\Settings\SiteSetting;

class CommentsController extends Controller
{
    public function index()
    {
        $settings = SiteSetting::getSection('Comments', true);
        return view('core.settings.comments.index', compact('settings'));
    }

    public function save(Request $request)
    {
        if($request->ajax()) {
            foreach ($request->settings as $key => $setting) {
                SiteSetting::set('Comments', $key, $setting['value'], $setting['type']);
            }

            return response()->json([
                'status' => 'success',
            ], 201);
        }
    }
}
