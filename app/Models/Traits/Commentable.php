<?php
namespace App\Models\Traits;

use App\Models\Core\Comments\Comment;
use App\Models\Core\Comments\Subscriber;
use Auth;
use Purifier;
use Setting;

trait Commentable {

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable')->where('status', 1);
    }

    public function getComment($id)
    {
        return $this->comments()->where('id', $id)->first();
    }

    public function comment($request)
    {
        // dd($request->all());
            // $comment = Comment::create($request->all);

            if(Setting::get('Comments', 'must_approve')) {
                $request['status'] = Comment::PENDING;

                if(Setting::get('Comments', 'allow_approved')) {
                    $exists = Comment::where('email', $request->email)->where('status', Comment::APPROVED)->exists();

                    if($exists)
                        $request['status'] = Comment::APPROVED;
                }
            }
            else
                $request['status'] = Comment::APPROVED;

            if(Auth::user()){
                $request['user_id'] = Auth::user()->id;
                // $request['status'] = Comment::APPROVED;
            }

            $request['parent'] = $request->comment_parent;
            $request['visitor_ip'] = $request->ip();
            $comment = $this->comments()->create($request->all());
            $comment->subscribe($request);

            return $comment;
    }
}
