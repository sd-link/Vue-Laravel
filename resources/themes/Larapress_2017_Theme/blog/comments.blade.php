                <div class="panel panel-default" href="#" style="border-radius: 1px;">
                    <div class="panel-body" style="padding: 15px; padding-top: 18px;">
                        <div class="pull-left">
                            <b style="font-size: 24px;">{{$post->comments->count()}} Comments</b>
                        </div>
                        <div class="pull-right">
                            <button id="leave_reply" type="button" class="btn btn-primary" name="button">Leave a Reply</button>
                        </div>
                    </div>
                </div>

                @foreach($comments as $key => $comment)
                    <div id="comment-{{ $comment->id }}" class="panel panel-default" href="#" style="border-radius: 1px;">
                        <div class="panel-heading">
                            @if($comment->author)
                                <b>{{ $comment->author->firstname }} {{ $comment->author->lastname }}</b>
                            @else
                                <b>{{ $comment->name }}</b>
                            @endif
                        </div>
                        <div class="panel-body" style="padding: 15px;">
                            {!! $comment->body !!}
                        </div>
                        <div class="panel-footer" style="background: transparent; border: 0; padding-top: 0px; padding-bottom: 4px;">
                            @if( Setting::get('Comments', 'allow_nested') )
                                <div class="pull-right">
                                    <div data-comment-id="{{ $comment->id }}" class="reply_comment" style="cursor: pointer; margin-bottom: 5px;">
                                        reply
                                    </div>
                                </div>
                                <div style="clear: both;"></div>
                            @endif
                        </div>
                    </div>
                    @each('blog.partials.comment', $comment->childrenRecursive, 'comment')
                @endforeach

                <div id="comment_moderated" class="panel panel-default" style="border-radius: 2px; display: none;">
                    <div class="panel-body" >
                        <div class="form-group" style="margin-bottom: 0px;">
                            <b>Your comment has been posted and it held up for moderation.</b>
                        </div>
                    </div>
                </div>

                <div class="panel panel-default" href="#" style="border-radius: 2px;">
                    <div class="panel-body" style="padding: 15px;">
                        <h3>Leave a reply</h3>
                        <form class="" action="{{ route('frontend.blog.comment') }}" method="post">
                            <input type="hidden" name="content_id" value="{{ $post->id }}">
                            <input type="hidden" id="comment_parent" name="comment_parent" value="">
                            {{ csrf_field() }}

                            @if(Auth::guest())
                                <div class="form-group">
                                    <span>
                                        Thanks for choosing to leave a comment. Your email address will NOT be published.
                                        @if(Setting::get('Comments', 'must_approve'))
                                            Please keep in mind that all comments are moderated.
                                        @endif
                                    </span>
                                </div>

                                <div class="form-group" style="width: 31%; float: left;">
                                    <label for="">Name</label> *
                                    <input type="text" name="name" class="form-control">
                                </div>

                                <div class="form-group" style="width: 31%; float: left; margin-left: 22px;">
                                    <label for="">Email</label> *
                                    <input type="text" name="email" class="form-control">
                                </div>

                                <div class="form-group" style="width: 31%; float: left; margin-left: 22px;">
                                    <label for="">Website</label>
                                    <input type="text" name="website" class="form-control">
                                </div>

                                <div style="clear: both;"></div>
                            @else
                                <div class="form-group">
                                    Logged in as {{ Auth::user()->username }}
                                </div>
                            @endif

                            <div class="form-group">
                                <label for="">Comment</label>
                                <textarea name="body" class="form-control" style="height: 105px;"></textarea>
                            </div>

                            <div id="make_comment" class="form-group" style="width: 35%;">
                                <label for="subscribe">Subscribe</label>
                                <select name="subscribe" style="margin-top: 5px;">
                                    <option value="0">Choose subscription</option>
                                    <option value="1">All</option>
                                    <option value="2">New Comments</option>
                                    <option value="3">Replies to my comments</option>
                                    {{-- <option value="3">Remove any subscription</option> --}}
                                </select>
                            </div>
                            <button type="submit" name="button" class="pull-right btn btn-primary">Submit comment</button>
                        </form>
                    </div>
                </div>
@push('scripts')
    <script type="text/javascript">

        @if(Session::has('comment-posted'))
            @if(Session::pull('comment-status') == Comment::APPROVED)
                scrollToAnchor('comment-'+ {{ Session::pull('comment-posted') }})
            @else
                {{ Session::pull('comment-posted') }}
                $('#comment_moderated').show()
                scrollToAnchor('comment_moderated')
                console.log('comment moderated')
            @endif
        @endif

        $('.reply_comment').on('click', function(){
            $('#comment_parent').val($(this).data('comment-id'))
            scrollToAnchor("make_comment")
        })

        $('#leave_reply').on('click', function(){
            $('#comment_parent').val('')
            scrollToAnchor("make_comment")
        })

        function scrollToAnchor(anchor){
            var scrollTo = $("#"+ anchor);
            $('html,body').animate({scrollTop: scrollTo.offset().top},'slow');
        }
    </script>
@endpush
