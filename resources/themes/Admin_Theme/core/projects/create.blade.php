@extends('layouts.admin')

@section('content')
    <section class="content">
        <todos-editor>
        </todos-editor>
    </section>
@endsection

@push('scripts')
    <script src="{{ asset('js/todos_editor.js') }}?{{ str_random(7) }}"></script>
@endpush
