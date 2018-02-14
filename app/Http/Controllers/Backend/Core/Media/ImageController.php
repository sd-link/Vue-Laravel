<?php

namespace App\Http\Controllers\Backend\Core\Media;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Core\Media\Gallery;
use App\Models\Core\Media\Image;
use App\Models\Core\Media\ImageTag;

use Auth;
use Validator;
use Carbon\Carbon;
use ImgManager;
use Session;

class ImageController extends Controller
{

    public function index(Request $request)
    {
        $search = $request->search;
        $filter = $request->filter;
        $page = $request->page;
        $append = array();

        $images = Image::with('author')->with('tags')->latest();

        if($search){
            switch ($filter) {

                case 'username':
                    $images->whereHas('author', function($query) use($search) {
                        $query->where('username', 'LIKE', '%'. $search . '%');
                    });
                break;

                case 'title':
                    $images->where('title', 'LIKE', '%'. $search . '%');
                break;
            }
            $append += array('search' => $search);
            $append += array('filter' => $filter);
        }

        $images = $images->paginate(5);
        $images->appends($append);

        if($images->lastPage() && !$images->count()) {
            return redirect($images->previousPageUrl());
        }

        $allCount = Image::count();
        $returnedCount = $images->total();

        $publishedCount = null;
        $scheduledCount = null;
        $draftCount = null;

        $tags = ImageTag::select('id', 'title')->get();

        if($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'content' => view('core.media.images.includes.content', compact('images'))->render(),
                'counts' => view('core.shared.counts', compact('allCount', 'returnedCount', 'publishedCount', 'scheduledCount', 'draftCount'))->render(),
                'pagination' => view('core.media.images.includes.pagination', compact('images'))->render(),
                'page' => $page,
            ], 201);
        }

        return view('core.media.images.index', compact('images', 'page', 'tags', 'allCount', 'returnedCount', 'search', 'filter'));
    }

    /**
     * Returns the image
     *
     * @return \Illuminate\Http\Response
     */
    public function get(Request $request)
    {
        if($request->ajax()) {
            $image = Image::find($request->image_id);

            if($image) {
                return response()->json([
                    'status' => 'success',
                    'image' => $image,
                ], 201);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'There were problems trying to read this image.'
                ], 403);
            }
        }
    }

    public function delete(Request $request)
    {
        $image = Image::find($request->id);

        // Delete the image
        if($image->delete()) {
            return response()->json([
                'status' => 'success'
            ], 201);
        } else {
            return response()->json([
                'status' => 'error'
            ], 403);
        }

    }

    /**
     * Quick updates the image
     *
     * @return \Models\Backend\Blog\Page
     */
    protected function update(Request $request)
    {
        $image = Image::find($request->id);

        if(!$image) {
            return response()->json([
                'status' => 'error',
                'message' => 'Could not update this image. Does it still exists?'
            ], 404);
        }

        $image->title = $request->title;
        $image->caption = $request->caption;
        $image->alt = $request->alt;
        $image->slug = $request->slug;
        $image->tags = $request->tags;
        $image->save();

        $image->tags;
        $tags = ImageTag::all();

        return response()->json([
            'status' => 'success',
            'image' => $image,
            'tags' => $tags
        ], 201);
    }
}
