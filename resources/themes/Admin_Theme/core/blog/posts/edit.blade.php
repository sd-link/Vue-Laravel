@extends('layouts.admin')

@section('content')
        @include('partials/breadcrumb')
        <section class="content {{ Setting::getDeviceType() }}">
            <div class="row">
                <div class="col-md-9">
                    @component('components.content.edit',[
                        'mode' => 'edit',
                        'title' => 'Edit Post',
                        'type' => 'Post',
                        'content' => $post,
                        'viewurl' => route('frontend.blog.show', $post->slug),
                        'saveurl' => route('backend.blog.update'),
                        'show_categories' => Setting::get('Admin.Content', 'blog_single_show_categories'),
                        'show_tags' => Setting::get('Admin.Content', 'blog_single_show_tags'),
                        'categories' => $categories,
                        'tags' => $tags
                    ])

                        @slot('tabcontent')
                            <div id="tabContent">
                                {{-- @component('components.content.editor',['mode' => 'edit', 'content' => $post])@endcomponent --}}

                                <content-blocks></content-blocks>
                            </div>
                        @endslot
                    @endcomponent
                </div>
                <div class="col-md-3">
                    @component('components.content.savebox',[
                        'mode' => 'edit',
                        'content' => $post,
                        'show_author' => Setting::get('Admin.Content', 'blog_single_show_author'),
                        'show_access' => Setting::get('Admin.Content', 'blog_single_show_access'),
                        'show_password' => Setting::get('Admin.Content', 'blog_single_show_password')
                    ])
                    @endcomponent

                    @component('components.content.infobox',[
                        'mode' => 'edit',
                        'content' => $post,
                        'title' => 'Post Info',
                        'show_info' => Setting::get('Admin.Content', 'blog_single_show_post_info')
                    ])
                    @endcomponent

                    <featured-image
                        :init-content-id={{ $post->id }}
                        @if($post->featuredimage)
                            :init-image-id={{ $post->featuredimage->id}}
                            init-image-url={{ $post->featuredimage->medium }}
                        @endif
                        >
                    </featured-image>

                    <media-library init-upload-url="{{ route('backend.media.upload')}}">

                    </media-library>

                    {{-- @component('components.media.featured',[
                        'mode' => 'edit',
                        'content' => $post,
                        'show' => Setting::get('Admin.Content', 'blog_single_show_featured_image')
                    ])
                    @endcomponent --}}
                </div>
            </div>
        </section>
@endsection
