@component('components.content.content', [
    'class' => $settings->indexPageDisplayContentAs . ' page',
])
    {{-- Header --}}
    {{-- @slot('header')
        @include('core.content.includes.header')
    @endslot --}}

    {{-- DATA --}}
    @slot('content')
        @foreach ($content as $single)
            <div class="row @if($settings->indexPageDisplayContentAs == 'grid'){{$settings->indexPageGridColumns}}@endif" id="item{{ $single->id }}">
                {{-- STATUS --}}
                @if($settings->indexPageShowStatus)
                    <div class="column status_column">
                        @if($single->status == Content::PUBLISH)
                            <span class="label-published">Published</span>
                        @elseif($single->status == Content::DRAFT)
                            <span class="label-draft">Draft</span>
                        @elseif($single->status == Content::SCHEDULE)
                            <span class="label-schedule">Scheduled</span>
                        @endif
                    </div>
                @endif

                {{-- AUTHOR --}}
                @if($settings->indexPageShowAuthor)
                    <div class="column author_column" @if($settings->indexPageShowStatus != '1' && $settings->indexPageDisplayContentAs == 'grid') style="text-align: left;"@endif>
                        <a class="content_author" href="?search={{$single->author->username}}&filter=username">{{ $single->author->username }}</a>
                    </div>
                @endif

                {{-- TITLE --}}
                @if($settings->indexPageDisplayContentAs == 'grid')
                    @if($settings->indexPageShowFeaturedImage)
                        <div class="column title_column">
                            <a href="{{ route('backend.content.edit', [$type->slug, $single->id]) }}" class="content_title">{{$single->title}}</a>
                        </div>
                    @else
                        <div class="column title_column" style="height: {{$settings->indexPageTitleHeight}}px; justify-content: {{$settings->indexPageTitlePosition}}; align-items: {{$settings->indexPageTitlePosition}};">
                            <a href="{{ route('backend.content.edit', [$type->slug, $single->id]) }}" class="content_title">{{$single->title}}</a>
                        </div>
                    @endif
                @else
                    <div class="column title_column">
                        <a href="{{ route('backend.content.edit', [$type->slug, $single->id]) }}" class="content_title">{{$single->title}}</a>
                    </div>
                @endif

                {{-- FEATURED --}}
                @if($settings->indexPageShowFeaturedImage)
                    @if($settings->indexPageDisplayContentAs == 'grid')
                        <div class="column featured_image_column" style="height:{{ $settings->indexPageFeaturedImageHeight }}px;">
                            @if($single->featuredimage)
                                <img class="featured_image img-responsive" src="{{ $single->featuredimage->grid_large }}">
                            @else
                                <img class="featured_image img-responsive" style="border: 1px dashed rgba(0,0,0, 0.09);" src="{{ asset('images/no_featured_default.jpg') }}">
                            @endif
                        </div>
                    @else
                        <div class="column featured_image_column">
                            @if($single->featuredimage)
                                <img class="featured_image img-responsive" src="{{ $single->featuredimage->grid_large }}">
                            @else
                                <img class="featured_image img-responsive" style="border: 1px dashed rgba(0,0,0, 0.09);" src="{{ asset('images/no_featured_default.jpg') }}">
                            @endif
                        </div>
                    @endif
                @endif

                {{-- CREATED --}}
                @if($settings->indexPageShowCreatedUpdated)
                    <div class="column created_column">
                        <span class="label-created_at">Created</span> {{$single->created_at}}
                    </div>

                    <div class="column updated_column">
                        <span class="label-updated_at">Updated</span> <span class="content_updated_at">{{$single->updated_at}}</span>
                    </div>
                @endif

                {{-- ACTIONS --}}
                <div class="column actions_column">
                    <a href="{{ route('backend.content.edit', [$type->slug, $single->id]) }}" class="btn btn-xs btn-primary action-btn"><i class="fa fa-edit"></i></a>

                    <button
                        class="btn btn-xs btn-danger action-btn"
                        data-id="{{ $single->id }}"
                        data-title="{{ $single->title }}"
                        data-toggle="modal"
                        data-target="#deleteModal"
                        type="button"><i class="fa fa-trash"></i>
                    </button>
                </div>
            </div>
        @endforeach
    @endslot

    {{-- Footer --}}
    @slot('header')
        @include('core.content.includes.header')
    @endslot

@endcomponent
