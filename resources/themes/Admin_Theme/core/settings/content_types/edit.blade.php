@extends('layouts.admin')

@section('content')
        <section class="content {{ SiteSetting::getDeviceType() }}">
            <div class="row">
                <content-type-editor
                    :init-id={{ $contentType->id }}
                    init-name="{{ $contentType->name }}"
                    init-name-singular="{{ $contentType->name_singular }}"
                    init-mode="edit"
                    >
                </content-type-editor>

                <content-type-options v-bind="{!! $contentType->getSettingsFilteredForVue() !!}"></content-type-options>
            </div>
        </section>
@endsection

@push('scripts')
    <script src="{{ asset('js/content_type_editor.js') }}?{{ str_random(7) }}"></script>
@endpush
