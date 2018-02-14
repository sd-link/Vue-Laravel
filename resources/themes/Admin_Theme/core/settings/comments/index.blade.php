@extends('layouts.admin')

@section('content')
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <comments-settings v-bind="{!! $settings !!}"></comments-settings>
                </div>
            </div>
        </section>
@endsection

@push('scripts')
    <script src="{{ asset('js/settings.js') }}?{{ str_random(7) }}"></script>
@endpush
