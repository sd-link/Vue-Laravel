<?php

namespace App\Http\Controllers\Backend\Core\Pages;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Core\Pages\Page;
use Carbon\Carbon;
use Session;
use Content;
use Auth;
use Timezone;

class PageController extends Controller
{

    public function index(Request $request)
    {
        $search = $request->search;
        $filter = $request->filter;
        $status = $request->status;
        $page = $request->page;
        $append = array();

        $content = Page::with('author')->oldest();

        if($status){
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

        if($search){
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

        $content = $content->paginate(3);
        $content->appends($append);

        if($content->lastPage() && !$content->count()) {
            return redirect($content->previousPageUrl());
        }

        $allCount = Page::count();
        $publishedCount = Page::where('status', Content::PUBLISH)->count();
        $draftCount = Page::where('status', Content::DRAFT)->count();
        $scheduledCount = Page::where('status', Content::SCHEDULE)->count();
        $returnedCount = $content->total();

        $content->allCount = $allCount;
        $content->returnedCount = $returnedCount;
        $content->publishedCount = $publishedCount;
        $content->draftCount = $draftCount;
        $content->scheduledCount = $scheduledCount;

        return view('core.pages.index', compact('content', 'page', 'search', 'filter'));
    }

    public function create()
    {
        return view('core.pages.create');
    }

    /**
     * Show edit page form.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $page = Page::with('featuredImage')->where('id', $id)->first();

        return view('core.pages.edit', compact('page'));
    }

    /**
     * Updates the page
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $page = $this->save($request);

        if($page) {
            return response()->json([
                'status' => 'success',
                'content' => $page,
            ], 201);
        }
    }

    /**
     * Saves the Page by either creating a new one or updating existing one
     *
     * @return \Models\Backend\Pages\Page
     */
    protected function save($request)
    {
        $this->validate($request, [
            'title' => 'required|max:160'
        ]);

        $page = Page::firstOrNew(['id' => $request->id]);

        if($request->id == null && Session::has('featured_image'))
            $page->featured_image_id = Session::pull('featured_image');

        $page->title = $request->title;
        $page->body = $request->content;
        $page->status = $request->status;
        $page->access = $request->access;
        $page->published_at = $request->published_at;
        $page->slug = $request->slug;
        $page->password = $request->password;

        if($page->user_id == null)
            $page->user_id = Auth::user()->id;

        $page->save();
        // $page->translate('title', 'sv', '555');
        // dd($page->translate('title', 'sv'));

        return $page;
    }

    public function delete(Request $request)
    {
        $page = Page::find($request->page_id);

        // Delete the page
        if($page->delete()){
            return response()->json([
                'status' => 'success'
            ], 201);
        } else{
            return response()->json([
                'status' => 'error',
                'message' => 'Could not delete this page. Does it still exists?'
            ], 403);
        }

    }


}
