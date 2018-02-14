<?php

namespace App\Http\Controllers\Backend\Core\Comments;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Core\Comments\Comment;
use Session;

class CommentController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->status;
        $page = $request->page;
        $append = array();

        $comments = Comment::latest();

        if($status) {
            switch ($request->status) {
                case 'approved':
                    $comments = $comments->approved();
                break;

                case 'pending':
                    $comments = $comments->pending();
                break;

                case 'spam':
                    $comments = Comment::spam();
                break;

            }
            $append += array('status' => $status);
        }

        $comments = $comments->orderBy('id', 'desc')->paginate(3);
        $comments->appends($append);

        $commentsCount = Comment::latest()->count();
        $approvedCount = Comment::where('status', Comment::APPROVED)->count();
        $pendingCount = Comment::where('status', Comment::PENDING)->count();
        $spamCount = Comment::where('status', Comment::SPAM)->count();
        $returnedCommentsCount = $comments->total();

        if($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'comments' => view('core.comments.comments', compact('comments'))->render(),
                'counts' => view('core.comments.counts', compact('commentsCount', 'approvedCount', 'pendingCount', 'spamCount', 'returnedCommentsCount'))->render(),
                'pagination' => view('core.comments.pagination', compact('comments'))->render(),
                'page' => $page,
            ], 201);
        }

        return view('core.comments.index', compact('comments', 'commentsCount', 'approvedCount', 'pendingCount', 'spamCount', 'returnedCommentsCount', 'page', 'status'));
    }

    public function status(Request $request)
    {
        if($request->ajax()) {
            $comment = Comment::where('id', $request->comment_id)->first();
            $comments = Comment::latest()->paginate(3);
            $comment->status = $request->status;
            $comment->save();
            return response()->json([
                'status' => 'success',
                'comment' => $comment,
            ], 201);
        }
    }

    public function delete(Request $request)
    {
        if($request->ajax()) {
            $comment = Comment::where('id', $request->comment_id)->first();
            $comment->delete();
            return response()->json([
                'status' => 'success',
            ], 201);
        }
    }
}
