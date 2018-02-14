@extends('layouts.admin')

@section('content')
        <media-library></media-library>
        <section class="content {{ SiteSetting::getDeviceType() }}">
            <content-template-editor
                init-editor-mode="create"
                :init-content-types=" {{ $contentTypes }}"
                v-bind="{!! $editorSettings !!}">
            </content-template-editor>
        </section>
@endsection

@push('scripts')
    <script src="{{ asset('js/content_template_editor.js') }}?{{ str_random(7) }}"></script>
@endpush
