@extends('layouts.admin')

@section('content')
        <section class="content">
            <div class="row">
                <content-type-editor init-mode="create">
                </content-type-editor>

                <content-type-options></content-type-options>
            </div>
        </section>
@endsection

@push('scripts')
    <script src="{{ asset('js/content_type_editor.js') }}?{{ str_random(7) }}"></script>
@endpush
