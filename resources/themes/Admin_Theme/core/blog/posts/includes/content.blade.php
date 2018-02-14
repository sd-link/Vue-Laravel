@component('components.content.content', [
    'class' => 'blog',
])
    {{-- Header --}}
    @slot('header')
        @include('core.blog.posts.includes.header')
    @endslot

    {{-- DATA --}}
    @slot('content')
        @foreach ($content as $single)
            <div class="row" id="item{{ $single->id }}">
                {{-- STATUS --}}
                @component('components.content.columns.status', [
                    'single' => $single,
                    'show' => Setting::get('Admin.Content', 'blog_index_show_status')
                ])
                @endcomponent

                {{-- AUTHOR --}}
                @component('components.content.columns.author', [
                    'single' => $single,
                    'show' => Setting::get('Admin.Content', 'blog_index_show_author'),
                    'index_url' => route('backend.media.galleries.index', $single->id)
                ])
                @endcomponent

                {{-- TITLE --}}
                @component('components.content.columns.title', [
                    'single' => $single,
                    'edit_url' => route('backend.blog.edit', $single->id)
                ])
                @endcomponent

                {{-- FEATURED --}}
                @component('components.content.columns.featured', [
                    'single' => $single,
                    'show' => Setting::get('Admin.Content', 'blog_index_show_featured')
                ])
                @endcomponent

                {{-- CATEGORIES --}}
                @component('components.content.columns.categories', [
                    'single' => $single,
                    'show' => Setting::get('Admin.Content', 'blog_index_show_categories')
                ])
                @endcomponent

                {{-- TAGS --}}
                @component('components.content.columns.tags', [
                    'single' => $single,
                    'show' => Setting::get('Admin.Content', 'blog_index_show_tags')
                ])
                @endcomponent

                {{-- CREATED --}}
                @component('components.content.columns.created', [
                    'single' => $single,
                    'show' => Setting::get('Admin.Content', 'blog_index_show_created')
                ])
                @endcomponent

                {{-- UPDATED --}}
                @component('components.content.columns.updated', [
                    'single' => $single,
                    'show' => Setting::get('Admin.Content', 'blog_index_show_updated')
                ])
                @endcomponent

                {{-- ACTIONS --}}
                @component('components.content.columns.actions', [
                    'single' => $single
                ])
                @endcomponent
            </div>
        @endforeach
    @endslot

    {{-- Footer --}}
    @slot('header')
        @include('core.blog.posts.includes.header')
    @endslot

@endcomponent
