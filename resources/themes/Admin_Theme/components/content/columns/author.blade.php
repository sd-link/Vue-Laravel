
<div class="column author_column" @if(!$show) style="display: none;" @endif>
    <a class="content_author" href="{{ $index_url }}?search={{$single->author->username}}&filter=username">{{ $single->author->username }}</a>
</div>
