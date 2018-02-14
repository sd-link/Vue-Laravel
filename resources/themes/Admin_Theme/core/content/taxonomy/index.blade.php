@extends('layouts.admin')

@section('content')
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <taxonomy-editor :init-taxonomy="{{ $tax }}"></taxonomy-editor>
                </div>

                {{-- <div class="col-xs-6">
                  <div class="box">
                    <div class="box-header">
                        <h3 id="actionTitle" class="box-title">Add Tags</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <form id="addTagForm" action="">
                            <div id="title-form-group" class="form-group">
                                {{ csrf_field() }}
                                <input type="hidden" id="id" name="id" value="">

                                <label for="title">Title</label>
                                <input type="text" placeholder="Enter tag title" value="" id="title" name="title" class="form-control">
                                <span id="title-feedback-block" class="help-block" style="display: none;">
                                    <strong id="title-feedback"></strong>
                                </span>

                            </div>

                            <div class="form-group slug-group" style="display: none;">
                                <label for="title">Slug</label>
                                <input type="text" id="slug" name="slug" class="form-control" placeholder="Enter slug">
                            </div>

                            <div class="form-group">
                                <label for="title">Description</label> <small>(optional)</small>
                                <textarea id="description" name="description" class="form-control" placeholder="Enter tag description"></textarea>
                            </div>
                        </form>
                    </div>

                    <div class="box-footer">
                        <button id="actionTagBtn" data-action="create" class="btn btn-primary pull-left" type="submit">Add Tag</button>
                        <button id="resetTagBtn" class="btn btn-primary pull-right" type="submit" style="display: none">Back To Add</button>
                    </div>

                    <!-- /.box-body -->
                  </div>

                        <div id="tag-feedback-info" class="callout callout-info" style="display: none;">
                            <p id="tag-feedback-text"></p>
                        </div>

                </div> --}}
            </div>
        </section>
@endsection

@push('scripts')
    <script src="{{ asset('js/taxonomy_editor.js') }}?{{ str_random(7) }}"></script>
@endpush
