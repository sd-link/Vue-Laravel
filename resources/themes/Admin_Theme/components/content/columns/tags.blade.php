

<div class="column tags_column" @if(!$show) style="display: none;" @endif>
    @foreach ($single->tags as $tag)
        <a href="?search={{ $tag->title }}&filter=tag">
            <div class="tag">{{$tag->title}}</div>
        </a>
    @endforeach
</div>
