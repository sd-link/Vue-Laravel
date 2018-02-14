@extends('layouts.admin')

@section('content')
        @include('partials/breadcrumb')
        <section class="content {{ SiteSetting::getDeviceType() }}">
            <div class="row">
                <div class="col-md-9">
                    @component('components.content.edit',[
                        'mode' => 'create',
                        'title' => 'Create Post',
                        'saveurl' => route('backend.blog.update'),
                        'editurl' => route('backend.blog.edit', 0),
                        'show_categories' => false,
                        'show_tags' => false,
                        'categories' => null,
                        'tags' => null
                    ])

                        @slot('navtabs')
                            <li class="editor-tab active"><a data-toggle="tab" href="#tabContent" aria-expanded="true">Content</a></li>
                        @endslot

                        @slot('tabcontent')
                            <div id="tabContent" class="tab-pane active">
                                @component('components.content.editor',['mode' => 'create'])@endcomponent
                            </div>
                        @endslot

                    @endcomponent
                </div>
                <div class="col-md-3">

                    @component('components.content.savebox',[
                        'mode' => 'create',
                        'show_author' => false,
                        'show_access' => true,
                        'show_password' => false
                    ])
                    @endcomponent
                </div>
            </div>
        </section>
@endsection


@push('scripts')

    {{-- <script src="https://cdn.ckeditor.com/4.5.7/standard/ckeditor.js"></script> --}}
    {{-- <script src="http://cdn.ckeditor.com/4.6.1/standard-all/ckeditor.js"></script> --}}

@endpush
