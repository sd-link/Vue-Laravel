@extends('layouts.admin')

@section('content')
        @include('partials/breadcrumb')

        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                  <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Media Tags</h3>
                    </div>

                    <div class="box-body">
                        <div id="content_display" class="grid">
                            <div class="row column-2">
                                <div class="column title_column page_slug page"><a href="{{ route('backend.media.gallery.tags.index') }}" class="content_title">Gallery Tags</a></div>
                            </div>
                            <div class="row column-2">
                                <div class="column title_column page_slug page"><a href="{{ route('backend.media.image.tags.index') }}" class="content_title">Image Tags</a></div>
                            </div>
                        </div>
                    </div>
            </div>
        </section>
@endsection
