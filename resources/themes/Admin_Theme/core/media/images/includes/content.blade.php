<content-data inline-template>

    <div id="content_display" ref="contentList" class="grid image" v-replace-class="contentClass">
        {{-- Header --}}
        <div class="row-header">
            <div class="column author_column">Author</div>
            <div class="column title_column">Title</div>
            <div class="column featured_image_column">Image</div>
            <div class="column caption_column">Caption</div>
            <div class="column actions_column">Actions</div>
        </div>
        {{-- DATA --}}
        @foreach ($images as $image)
        <div class="row" v-replace-class="contentColumnClass" id="item{{ $image->id }}">

            {{-- AUTHOR --}}
            <div class="column author_column">
                <a href="{{ route('backend.media.images.index') }}?search={{ $image->author->username }}&filter=username">{{ $image->author->username }}</a>
            </div>

            {{-- TITLE --}}
            <div class="column title_column">
                <div class="image_title">
                    {{ $image->title }}
                </div>
            </div>

            {{-- IMAGE --}}
            <div class="column featured_image_column">
                <img class="featured_image img-responsive" src="{{ $image->grid_large }}">
            </div>

            <div class="column caption_column">
                {{ $image->caption }}
            </div>

            {{-- CREATED --}}
            <div class="column created_column">
                <span class="label-created_at">Created</span> {{ $image->created_at }}
            </div>

            {{-- UPDATED --}}
            <div class="column updated_column">
                <span class="label-updated_at">Updated</span>  <span class="image_updated_at">{{$image->updated_at}}</span>
            </div>

            {{-- ACTIONS --}}
            <div class="column actions_column">
                <button class="btn btn-xs btn-primary action-btn"
                    type="button"><i class="fa fa-edit"></i>
                </button>

                <button
                    class="btn btn-xs btn-danger action-btn"
                    type="button"><i class="fa fa-trash"></i>
                </button>
            </div>
        </div>
        @endforeach

        {{-- Header --}}
        <div class="row-header">
            <div class="column author_column">Author</div>
            <div class="column title_column">Title</div>
            <div class="column featured_image_column">Image</div>
            <div class="column caption_column">Caption</div>
            <div class="column actions_column">Actions</div>
        </div>
    </div>
</content-data>
