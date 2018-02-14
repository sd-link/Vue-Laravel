@extends('layouts.admin')

@section('content')
    <media-library></media-library>
    <section class="content {{ SiteSetting::getDeviceType() }}">
        ...9{{ $template->default }}9...
        <content-template-editor
            init-editor-mode="edit"
            :init-template-id={{ $template->id }}
            :init-default-template={{ $template->default ? 'true' : 'false' }}
            init-template-name="{{ $template->name }}"
            :init-content-type-id={{ isset($template->content_type_id) ? $template->content_type_id : 0 }}
            :init-content-types="{{ $contentTypes }}"
            v-bind="{!! $editorSettings !!}">
        </content-template-editor>
    </section>
@endsection

@push('scripts')
    <script src="{{ asset('js/content_template_editor.js') }}?{{ str_random(7) }}"></script>
@endpush
