<div id="comment-{{ $comment->id }}" class="panel panel-default" href="#" style="border-radius: 1px; margin-left: 30px;">
    <div class="panel-heading">
        @if($comment->author)
            <b>{{ $comment->author->firstname }} {{ $comment->author->lastname }}</b>
        @else
            <b>{{ $comment->name }}</b>
        @endif
    </div>
    <div class="panel-body" style="padding: 15px;">
        {{ $comment->body }}
    </div>
    @if( $comment->level < Setting::get('Comments', 'nested_level') && Setting::get('Comments', 'allow_nested') )
        <div class="panel-footer" style="background: transparent; border: 0; padding-top: 0px; padding-bottom: 4px;">
            <div class="pull-right">
                <div data-comment-id="{{ $comment->id }}" class="reply_comment" style="cursor: pointer; margin-bottom: 5px;">
                    reply
                </div>
            </div>
            <div style="clear: both;">

            </div>
        </div>
    @endif
</div>

@if (count($comment['childrenRecursive']) > 0)
    <ul>
    @foreach($comment['childrenRecursive'] as $comment)
        @include('blog.partials.comment',  ['comment' => $comment])
    @endforeach
    </ul>
@endif
