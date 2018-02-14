<?php

namespace App\Http\Controllers\Backend\Core\Media;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Core\Base\Content;
use App\Models\Core\Media\Gallery;
use App\Models\Core\Media\Image;

use Session;
use Auth;
use Validator;
use Carbon\Carbon;
use ImgManager;

class MediaController extends Controller
{

    public function images(Request $request)
    {
        $search = $request->search;
        $filter = $request->filter;
        $append = array();

        $images = Image::latest();

        if($request->order == 'oldest')
            $images = Image::oldest();

        if($search) {
            switch ($filter) {
                case 'galleries':
                    $images->with('galleries')->whereHas('galleries', function ($query) use ($search) {
                        $query->where('slug', $search);
                    });
                break;

                case 'gallerytag':
                    $images->with('galleries')->whereHas('galleries', function ($query) use ($search) {
                        $query->whereHas('tags', function ($query) use ($search) {
                            $query->where('slug', $search);
                        });
                    });
                break;

                // case 'username':
                //     $images->whereHas('author', function($query) use($search) {
                //         $query->where('username', 'LIKE', '%'. $search . '%');
                //     });
                // break;

                default:
                    # code...
                break;
            }
            $append += array('search' => $search);
            $append += array('filter' => $filter);
        }

        // $images = Image::where('id', '!=', $image_id)->select('id', 'path', 'filename', 'extension', 'class_name')->orderBy('created_at', 'desc')->paginate(20);

        $images = $images->select('id', 'path', 'filename', 'extension', 'class_name', 'user_id')->paginate(27);

        // $image = Image::where('id', $image_id)->get();
        $total = $images->total();

        if($total > 0) {
            return response()->json([
                'status' => 'success',
                'images' => $images,
                'total' => $total,
                'append' => $append
                // 'img' => $imgs,
            ], 201);
        } else {
            return response()->json([
                'status' => 'success',
                'images' => null,
                'append' => $append
            ], 201);
        }
    }

    public function tags()
    {
        return view('core.media.tags.index');
    }

    public function upload(Request $request)
    {
        // $album = new Album();
        // $album->title = $albumHash;

        // $album->user_id = Auth::user()->id;

        if($request->hasFile('file')) {
            $order = 1;
            $imageIds = array();
            // $images = array();
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
                    $animated = preg_match('#(\x00\x21\xF9\x04.{4}\x00\x2C.*){2,}#s', $giftest);

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

                    // Generate thumbnail image
                    $thumbnailImageName = $filename .'_thumb.'. $fileExtension;
                    $thumbnailImage = ImgManager::make($folder . $fileFullName)->fit(220, 220);
                    $thumbnailImage->save( $folder . $thumbnailImageName);

                    $thumbnailImageName = $filename .'_thumb_large.'. $fileExtension;
                    $thumbnailImage = ImgManager::make($folder . $fileFullName)->fit(480, 480);
                    $thumbnailImage->save( $folder . $thumbnailImageName);

                    // $image->thumbnail_meta_width = 150;
                    // $image->thumbnail_meta_height = 150;

                    // Generate grid style image
                    $gridColumnImageMediumName = $filename .'_grid_medium.'. $fileExtension;
                    $gridColumnImageMedium = ImgManager::make($folder . '/'.$fileFullName)->fit(600, 400);
                    $gridColumnImageMedium->save( $folder . $gridColumnImageMediumName);

                    $gridColumnImageLargeName = $filename .'_grid_large.'. $fileExtension;
                    $gridColumnImageLarge = ImgManager::make($folder . '/'.$fileFullName)->fit(1200, 800);
                    $gridColumnImageLarge->save( $folder . $gridColumnImageLargeName);

                    // Generate medium size image
                    $mediumImageName = $filename .'_medium.'. $fileExtension;
                    $mediumImage = ImgManager::make($folder . '/'.$fileFullName)->resize($resizeWidthMedium, $resizeHeightMedium, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                    $mediumImage->save( $folder . $mediumImageName);

                    // $image->medium_meta_width = $mediumImage->width();
                    // $image->medium_meta_height = $mediumImage->height();

                    // Generate large size image
                    $largeImageName = $filename .'_large.'. $fileExtension;
                    $largeImage = ImgManager::make($folder . '/'.$fileFullName)->resize($resizeWidthLage, $resizeHeightLage, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                    $largeImage->save( $folder . $largeImageName);

                    // $image->large_meta_width = $largeImage->width();
                    // $image->large_meta_height = $largeImage->height();

                    $image->path = $folder;
                    $image->filename = $filename;
                    $image->extension = $fileExtension;
                    $image->user_id = Auth::user()->id;
                    $image->save();

                    array_push($imageIds, $image->id);

                    // array_push($images, asset($folder.$fileFullName));
                } // validator
            } // foreach

            $images = Image::whereIn('id', $imageIds)->select('id', 'path', 'filename', 'extension')->orderBy('created_at', 'desc')->get();
            return response()->json([
                'status' => 'success',
                'images' => $images,
                // 'img' => $imgs,
            ], 201);
        } // if
    }
}
