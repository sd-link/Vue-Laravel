@extends('layouts.admin')

@section('content')
        <section class="content {{ SiteSetting::getDeviceType() }}">
            <div class="row">
                <gallery-editor init-mode="create"></gallery-editor>
            </div>
        </section>
@endsection

@push('scripts')
    <script src="{{ asset('js/gallery_editor.js') }}?{{ str_random(7) }}"></script>
@endpush
