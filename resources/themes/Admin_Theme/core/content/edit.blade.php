@extends('layouts.admin')

@section('content')
    <media-library></media-library>
    <section class="content">
        <content-editor
            init-device-type={{ SiteSetting::getDeviceType() }}
            init-editor-mode="edit"
            init-content-title="{{ $content->title }}"
            :init-content-id={{ $content->id }}
            init-slug="{{ $content->slug }}"

            init-content-type="{{ $contentType->slug }}"
            :init-content-type-id={{ $contentType->id }}
            :content="{{ $content }}"

            init-published-at-date="{{ $content->published_at }}"
            init-created-at-date="{{ $content->created_at }}"
            init-updated-at-date="{{ $content->updated_at }}"
            v-bind="{!! $editorSettings !!}"
            v-bind="{!! $contentType->getSettingsFilteredForVue() !!}"

            :init-content-status="{{ $content->status }}"
            :init-saved="true">
        </content-editor>
    </section>
@endsection

@push('scripts')
    <script src="{{ asset('js/content_editor.js') }}?{{ str_random(7) }}"></script>
@endpush
