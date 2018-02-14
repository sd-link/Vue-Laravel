@extends('layouts.admin')

@section('content')
        <media-library></media-library>
        <section class="content {{ SiteSetting::getDeviceType() }}">
            <content-editor
                init-editor-mode="create"
                init-content-type="{{ $contentType->slug }}"
                :init-content-type-id={{ $contentType->id }}
                v-bind="{!! $editorSettings !!}"
                v-bind="{!! $contentType->getSettingsFilteredForVue() !!}">
            </content-editor>
        </section>
@endsection

@push('scripts')
    <script src="{{ asset('js/content_editor.js') }}?{{ str_random(7) }}"></script>
@endpush
