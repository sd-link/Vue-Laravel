@extends('layouts.admin')

@section('content')

        @include('partials/breadcrumb')
        @include('core/media/tags/add', ['url' => route('backend.media.image.tags.create')])
        @include('core/media/tags/edit', ['url' => route('backend.media.image.tags.update')])
        @include('core/media/tags/delete', ['url' => route('backend.media.image.tags.delete')])

        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                  <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Image Tags</h3>
                        <div class="box-tools">
                            <button class="btn btn-block btn-primary btn-sm" type="submit" value="Add New"
                            data-toggle="modal" data-target="#addNewTagModal">
                            <i class="fa fa-fw fa-plus"></i> Add New</button>
                        </div>
                    </div>

                    <div class="box-body">
                        <content-data inline-template
                            init-default-layout="list"
                            init-content-class="list page"
                            :init-is-desktop={{ SiteSetting::isDesktop() ? 'true':'false'}}>

                            <div id="content_display" ref="contentList" class="list page">
                                {{-- Header --}}
                                <div class="row-header">
                                    <div class="column status_column">Title</div>
                                    <div class="column actions_column">Actions</div>
                                </div>

                                {{-- DATA --}}
                                <div id="tagsList">
                                    @foreach ($tags as $tag)
                                    <div class="row" id="item{{ $tag->id }}">
                                        {{-- TITLE --}}
                                        <div class="column title_column page" style="color: rgb(60, 141, 188);">
                                            {{ $tag->title }}
                                        </div>

                                        {{-- ACTIONS --}}
                                        <div class="column actions_column" data-id="{{ $tag->id }}">
                                                <button class="btn btn-xs btn-primary action-btn"
                                                    data-id="{{ $tag->id }}"
                                                    data-title="{{ $tag->title }}"
                                                    data-slug="{{ $tag->slug }}"
                                                    data-description="{{ $tag->description }}"
                                                    data-toggle="modal"
                                                    data-target="#editTagModal"
                                                    type="button"><i class="fa fa-edit"></i></button>
                                                <button
                                                    class="btn btn-xs btn-danger action-btn"
                                                    data-toggle="modal"
                                                    data-id="{{ $tag->id }}"
                                                    data-title="{{ $tag->title }}"
                                                    data-target="#deleteModal"
                                                    type="button"><i class="fa fa-trash"></i></button>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>

                                {{-- Header --}}
                                <div class="row-header">
                                    <div class="column status_column">Title</div>
                                    <div class="column actions_column">Actions</div>
                                </div>
                            </div>
                        </content-data>

                        {{ $tags->links() }}
                    </div>
                    <!-- /.box-body -->
                  </div>
                </div>
            </div>
        </section>
@endsection
