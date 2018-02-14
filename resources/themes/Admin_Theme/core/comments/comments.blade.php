@foreach ($comments as $comment)
<tr @if($comment->status == 2) class="unapproved-comment" @endif>
    <td >
        @if($comment->author)
            @if($comment->author->firstname && $comment->author->lastname)
                <div>{{ $comment->author->firstname }} {{ $comment->author->lastname }}</div>
            @endif
            <div>
                {{-- <a href="{{ route('backend.content.index') }}?search={{$comment->author->username}}&filter=username">{{ $comment->author->username }}</a> --}}
                right said fred
            </div>
        @else
            <div>{{ $comment->name }}</div>
        @endif

        @if($comment->author)
            <div>{{ $comment->author->email }}</div>
        @else
            <div>{{ $comment->email }}</div>
        @endif

        <div>{{ $comment->visitor_ip }}</div>
    </td>

    <td>
        <div class="" style="margin-bottom: 10px;">
            {{ $comment->body }}
        </div>
        <div class="">
            @if($comment->status != Comment::SPAM)
                <span class="approve" data-comment-id="{{ $comment->id }}" style="@if($comment->status == Comment::APPROVED) display: none; @endif cursor: pointer; color: #2489C5">approve</span>
                <span class="unapprove" data-comment-id="{{ $comment->id }}" style="@if($comment->status == Comment::PENDING) display: none; @endif cursor: pointer; color: #2489C5">unapprove</span>
            @else
                <span class="not-spam" data-comment-id="{{ $comment->id }}" style="cursor: pointer; color: #2489C5">not spam</span>
            @endif

            @if($comment->status == Comment::APPROVED || $comment->status == Comment::PENDING)
                ·
                <span class="spam" data-comment-id="{{ $comment->id }}" style="cursor: pointer; color: #2489C5">spam</span>
            @endif
            ·
            <span class="delete" data-comment-id="{{ $comment->id }}" style="cursor: pointer; color: #2489C5">delete</span>
        </div>
    </td>

    <td data-keyboard="false">
        @if($comment->commentable_type == 'App\Models\Core\Blog\Post')
            @php
                $post = App\Models\Core\Blog\Post::where('id', $comment->commentable_id)->first();
            @endphp
            {{ $post->title }}
        @elseif($comment->commentable_type == 'App\Models\Core\Pages\Page')
            @php
                $page = App\Models\Core\Pages\Page::where('id', $comment->commentable_id)->first();
            @endphp
            {{ $page->title }}
        @else
            unknown type of content
        @endif
    </td>

    <td>
        {{ $comment->created_at }}
    </td>
</tr>
@endforeach

@if(!$comments->count())
<tr>
    <td id="no-comments" colspan="4">No comments.</td>
</tr>
@endif
