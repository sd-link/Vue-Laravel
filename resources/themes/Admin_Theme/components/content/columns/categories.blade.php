
    <div class="column categories_column" @if(!$show) style="display: none;" @endif>
        @foreach ($single->categories as $category)
            <div data-category="{{$category->id}}" class="category_id category">
                <a href="?search={{ $category->title }}&filter=category">
                    {{$category->title}}
                </a>
            </div>
        @endforeach
    </div>
