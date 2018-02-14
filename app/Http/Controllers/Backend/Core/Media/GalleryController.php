<?php

namespace App\Http\Controllers\Backend\Core\Media;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Core\Media\Gallery;
use App\Models\Core\Media\GalleryTag;
use App\Models\Core\Media\Image;

use App\Models\Core\Settings\SiteSetting;

use Content;
use Auth;
use Validator;
use Carbon\Carbon;
use ImgManager;
use Session;
use Timezone;

class GalleryController extends Controller
{
    public function get(Request $request)
    {
        $search = $request->search;
        $filter = $request->filter;
        $status = $request->status;
        $page = $request->page;
        $per_page = $request->per_page;

        $append = array();

        $galleries = Gallery::with('author')->with('images')->latest();

        // if($search) {
        //     switch ($filter) {
        //
        //         case 'username':
        //             $galleries->whereHas('author', function($query) use($search) {
        //                 $query->where('username', 'LIKE', '%'. $search . '%');
        //             });
        //         break;
        //
        //         case 'title':
        //             $galleries->where('title', 'LIKE', '%'. $search . '%');
        //         break;
        //
        //         default:
        //             # code...
        //         break;
        //     }
        //     $append += array('search' => $search);
        //     $append += array('filter' => $filter);
        // }

        $galleries = $galleries->paginate($per_page);


        foreach ($galleries as $key => $gallery) {
            $gallery->firstImage = $gallery->images->first();
        }

        foreach ($galleries as $key => $gallery) {
            unset($gallery->images);
        }
        // $galleries->appends($append);

        // if($galleries->lastPage() && !$galleries->count()) {
        //     return redirect($galleries->previousPageUrl());
        // }

        // $allCount = Gallery::count();
        // $returnedCount = $galleries->total();
        //
        // $galleries->allCount = $allCount;
        // $galleries->returnedCount = $returnedCount;

        return response()->json($galleries);
    }

    public function index()
    {
        $settings = SiteSetting::getSection('Core.Galleries', true);
        // dd($settings);
        return view('core.media.galleries.index', compact('settings'));
    }

    public function all(Request $request)
    {
        $galleries = Gallery::select('id', 'title', 'slug')->get();

        if($galleries->count()) {
            return response()->json([
                'status' => 'success',
                'galleries' => $galleries
            ], 201);
        } else {
            return response()->json([
                'status' => 'success',
                'galleries' => null
            ], 201);
        }
    }

    public function images(Request $request)
    {
        $gallery = Gallery::with('images')->where('id', $request->id)->first();

        return response()->json([
            'status' => 'success',
            'images' => $gallery->images,
        ], 201);
    }

    public function sessionImages(Request $request)
    {
        $imageIds = [];

        if(Session::has('images')) {
            foreach (Session::get('images') as $key => $imageId) {
               array_push($imageIds, $imageId);
            }
        }

        $images = Image::whereIn('id', $imageIds)->get();

        return response()->json([
            'status' => 'success',
            'images' => $images,
        ], 201);
    }

    public function order(Request $request)
    {
        $order = 1;
        $gallery = Gallery::where('id', $request->id)->first();

        foreach ($request->order as $key => $image_id) {
            $gallery->images()->updateExistingPivot($image_id,['order' => $order]);
            $order = $order + 1;
        }

        $gallery->save();

        return response()->json([
            'status' => 'success',
        ], 201);
    }

    /**
     * Show Create Gallery Form.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('core.media.galleries.create');
    }

    /**
     * Show edit gallery form.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $gallery = Gallery::with(array('images' => function ($query) {
                                $query->orderBy('gallery_image.order', 'asc');
                            }
                        ))->with('settings')->where('id', $id)->first();

        return view('core.media.galleries.edit', compact('gallery'));
    }


    /**
     * Updates the gallery
     *
     * @return \Illuminate\Http\Response
     */
    public function save(Request $request)
    {
        if($request->ajax()) {
            $gallery = Gallery::firstOrNew(['id' => $request->id]);

            $gallery->title = $request->title;
            $gallery->description = $request->description;
            $gallery->slug = $request->title;
            $gallery->user_id = Auth::user()->id;

            $gallery->save();

            // $gallery->tags = $request->tags;

            $gallery->pickImagesFromSession();

            // get all the tags, there could have been new created in the update
            // $tags = GalleryTag::all();

            // eager load this content tags
            // $gallery->tags;

            if($gallery) {
                return response()->json([
                    'status' => 'success',
                    'gallery' => $gallery,
                ], 201);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'There were problems trying to update this gallery.'
                ], 403);
            }
        }
    }

    /**
     * Delete the gallery.
     *
     * @return json response
     */
    public function delete(Request $request)
    {
        $gallery = Gallery::find($request->id);

        // Delete the gallery
        if($gallery->delete()){
            return response()->json([
                'status' => 'success'
            ], 201);
        } else{
            return response()->json([
                'status' => 'error',
                'message' => 'Could not delete this gallery. Does it still exists?'
            ], 403);
        }

    }

    public function upload(Request $request)
    {
        if($request->hasFile('file')) {
            // image order when added to the gallery
            $order = 1;
            $gallery = null;

            if($request->id) {
                $gallery = Gallery::with(array('images' => function ($query) {
                                $query->orderBy('gallery_image.order', 'desc');
                            }
                        ))->where('id', $request->id)->first();

                $image = $gallery->images->first();

                if($image)
                    $order += $image->pivot->order;
            }

            $imageIds = array();

            foreach($request->file('file') as $file) {
                $rules = array('file' => 'required|mimes:png,gif,jpeg,jpg'); //'mimes:png,gif,jpeg,txt,pdf,doc'
                $validator = Validator::make(array('file'=> $file), $rules);

                if($validator->passes()) {
                    $image = new Image();
                    $current = Carbon::now();
                    $year = $current->year;
                    $month = $current->month;
                    $day = $current->day;
                    $hour = $current->hour;
                    $folder = 'uploads/'.$year.'/'.$month.'/'.$day .'/';

                    if( ! file_exists($folder)) {
                        $oldmask = umask(0);
                        mkdir($folder, 0775, true);
                        umask($oldmask);
                    }

                    $filename = strtolower(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
                    $fileExtension = strtolower($file->getClientOriginalExtension());

                    $image->slug = $image->slugify($filename);
                    $filename = $image->slug;
                    $fileFullName = $filename . '.'. $fileExtension;

                    $file->move($folder, $fileFullName);
                    $giftest = file_get_contents($folder.'/'.$fileFullName);
                    // $animated = preg_match('#(\x00\x21\xF9\x04.{4}\x00\x2C.*){2,}#s', $giftest);

                    // if($watermark->body != "" && $animated!=1) {
                    //     // Add Watermark
                    //     $width = ImgResizer::make($folder . '/'.$name)->width();
                    //     $height = ImgResizer::make($folder . '/'.$name)->height();
                    //     ImgResizer::make($folder . '/'.$name)->text($watermark->body, $width-15, $height-10, function($font) {
                    //         $font->file(public_path('fonts/NunitoSans-Regular.ttf'));
                    //         $font->size(52);
                    //         $font->color('#ffffff');
                    //         $font->align('right');
                    //         $font->valign('bottom');
                    //     })->save();
                    // }

                    $width = ImgManager::make($folder . $fileFullName)->width();
                    $height = ImgManager::make($folder . $fileFullName)->height();

                    $resizeWidthMedium = null;
                    $resizeHeightMedium = null;
                    $resizeWidthLage = null;
                    $resizeHeightLage = null;

                    if($width > $height) {
                        $resizeWidthMedium = 600;
                        $resizeWidthLage = 1024;
                    }
                    else {
                        $resizeHeightMedium = 600;
                        $resizeHeightLage = 1024;
                    }

                    $thumbnailImageName = $filename .'_thumb.'. $fileExtension;
                    $thumbnailImage = ImgManager::make($folder . $fileFullName)->fit(220, 220);
                    $thumbnailImage->save( $folder . $thumbnailImageName);

                    $thumbnailImageName = $filename .'_thumb_large.'. $fileExtension;
                    $thumbnailImage = ImgManager::make($folder . $fileFullName)->fit(480, 480);
                    $thumbnailImage->save( $folder . $thumbnailImageName);

                    $gridColumnImageMediumName = $filename .'_grid_medium.'. $fileExtension;
                    $gridColumnImageMedium = ImgManager::make($folder . '/'.$fileFullName)->fit(600, 400);
                    $gridColumnImageMedium->save( $folder . $gridColumnImageMediumName);

                    $gridColumnImageLargeName = $filename .'_grid_large.'. $fileExtension;
                    $gridColumnImageLarge = ImgManager::make($folder . '/'.$fileFullName)->fit(1200, 800);
                    $gridColumnImageLarge->save( $folder . $gridColumnImageLargeName);

                    $mediumImageName = $filename .'_medium.'. $fileExtension;
                    $mediumImage = ImgManager::make($folder . '/'.$fileFullName)->resize($resizeWidthMedium, $resizeHeightMedium, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                    $mediumImage->save( $folder . $mediumImageName);

                    $largeImageName = $filename .'_large.'. $fileExtension;
                    $largeImage = ImgManager::make($folder . '/'.$fileFullName)->resize($resizeWidthLage, $resizeHeightLage, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                    $largeImage->save( $folder . $largeImageName);

                    $image->path = $folder;
                    $image->filename = $filename;
                    $image->extension = $fileExtension;
                    $image->user_id = Auth::user()->id;

                    $image->save();

                    // Save to session if image was uploaded without specifiying gallery
                    if(!$request->id)
                        Session::push('images', $image->id);
                    else { // or attach to a gallery
                        $gallery->images()->attach([$image->id => ['order' => $order]]);
                    }

                    $order = $order + 1;

                    array_push($imageIds, $image->id);

                    // array_push($images, asset($folder.$fileFullName));
                } // validator
            } // foreach

            $images = Image::whereIn('id', $imageIds)->select('id', 'class_name', 'path', 'filename', 'extension')->orderBy('created_at', 'desc')->get();

            return response()->json([
                'status' => 'success',
                'images' => $images,
                // 'img' => $imgs,
            ], 201);
        } // if
    }

    public function saveSettings(Request $request) {

        foreach ($request->settings as $key => $setting) {
            SiteSetting::set('Core.Galleries', $key, $setting['value'], $setting['type']);
        }

        return response()->json([
            'status' => 'success',
        ], 201);
    }

}
