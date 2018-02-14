
    <div id="galleries" class="grid gallery">
        {{-- Header --}}
        @include('core.media.galleries.includes.header')

        @foreach ($galleries as $single)
            <div class="row column-3" id="gallery-{{ $single->id }}">
                {{-- AUTHOR --}}
                <div class="column author_column">
                    <a class="content_author" href="{{ route('backend.media.galleries.index', $single->id) }}?search={{$single->author->username}}&filter=username">{{ $single->author->username }}</a>
                </div>

                {{-- TITLE --}}
                <div class="column title_column">
                    <a href="{{ route('backend.media.galleries.edit', $single->id) }}" class="content_title">{{$single->title}}</a>
                </div>

                {{-- FEATURED --}}
                <div class="column featured_image_column">
                    @if($single->images->first())
                        <img class="featured_image img-responsive" src="{{ $single->images->first()->grid_large }}">
                    @else
                        <img class="featured_image img-responsive" src="">
                    @endif
                </div>

                {{-- SHORTCODE --}}
                {{-- <div class="column shortcode_column">
                    [gallery id={{$single->id}}]
                </div> --}}

                {{-- IMAGE COUNT --}}
                {{-- <div class="column images_count_column">
                    Images: {{$single->images->count()}}
                </div> --}}

                {{-- CREATED --}}
                {{-- <div class="column created_column">
                    <span class="label-created_at">Created</span> {{$single->created_at}}
                </div> --}}


                {{-- UPDATED --}}
                {{-- <div class="column updated_column">
                    <span class="label-updated_at">Updated</span> <span class="content_updated_at">{{$single->updated_at}}</span>
                </div> --}}


                {{-- ACTIONS --}}
                <div class="column actions_column">
                    <button id="editBtn" class="btn btn-xs btn-primary action-btn" type="button" title="Edit">
                        <i class="fa fa-edit"></i>
                    </button>

                    <button
                        class="btn btn-xs btn-danger action-btn"
                        data-id="{{ $single->id }}"
                        data-title="{{ $single->title }}"
                        data-toggle="modal"
                        data-target="#deleteModal"
                        type="button"><i class="fa fa-trash" data-title="{{ $single->title }}"></i>
                    </button>
                </div>
            </div>
        @endforeach

        {{-- FOOTER --}}
        @include('core.media.galleries.includes.header')
    </div>
