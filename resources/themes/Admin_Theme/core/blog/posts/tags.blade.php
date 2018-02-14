@extends('layouts.admin')

@section('content')

        @include('partials/breadcrumb')

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                  <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Tags</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="tagsTable" class="table table-content">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="tagsList">
                                @foreach ($tags as $tag)
                                    <tr id="tagid{{ $tag->id }}">
                                        <td class="tag" data-id="{{ $tag->id }}" data-url="{{ route('backend.blog.tags.get', $tag->id)}}" style="color: #3c8dbc; cursor: pointer;">{{ $tag->title }}</td>
                                        <td style="width: 150px;">
                                            <button id="deleteBtn-{{ $tag->id }}" class="btn btn-xs btn-danger" type="submit"><i class="fa fa-trash"></i> Delete</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Title</th>
                                    <th>Actions</th>
                                </tr>
                            </tfoot>
                        </table>
                        {{ $tags->links() }}
                    </div>
                    <!-- /.box-body -->
                  </div>
                </div>

                <div class="col-xs-6">
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

                </div>
            </div>
        </section>
@endsection

@push('scripts')

<script type="text/javascript">
    $(document).ready(function() {
        initTags();
        function initTags() {

            $('.tag').unbind();
            $('.tag').click(function(event) {
                var id = event.target.getAttribute('data-id');
                var url = event.target.getAttribute('data-url');

                // console.log('id: '+id)
                // console.log('url: '+url)
                $.ajax(url, {
                    method: 'GET',
                    dataType:'json',
                    success: function(result) {
                        $("#actionTitle").html("Update Tag");
                        $("#title").val(result.title);
                        $("#description").val(result.description);
                        $("#id").val(result.id);
                        $('.slug-group').show();
                        $("#slug").val(result.slug);

                        $("#actionTagBtn").html("Update Tag");
                        $("#actionTagBtn").attr("data-action", "update");
                        $("#resetTagBtn").show();
                    },
                    error: function(result) {
                        alert('error');
                    }
                });
            });
        }

        $('#resetTagBtn').click(function(event) {
            // $('#parent').find('option[value=0]').text('No Parent');
            resetToAddTag();
        });

        function resetToAddTag() {
            $("#actionTitle").html("Add Tag");
            $("#id").val('');
            $("#title").val('');
            $("#description").val('');
            $("#actionTagBtn").attr("data-action", "create");
            $("#actionTagBtn").html("Add Tag");
            $('.slug-group').hide();
            $("#resetTagBtn").hide();
        }

        function initDeleteBtn() {
            console.log('initDeleteFunction');

            $('[id^=deleteBtn-]').unbind();
            $('[id^=deleteBtn-]').on("click", function(event){
                event.preventDefault();
                var id = event.target.id.slice(10);
                swal({
                    title: 'Wait!',
                    text: "Are you sure you want to delete this tag?",
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Delete',
                    allowOutsideClick: false,
                    confirmButtonClass: 'btn btn-success',
                    cancelButtonClass: 'btn btn-danger'
                }).then(function() {
                    $.ajax("{{route('backend.blog.tags.delete')}}", {
                        method: 'POST',
                        dataType:'json',
                        data: {
                            id: id,
                            _token: Laravel.csrfToken
                        },
                        success: function(result) {
                            resetToAddTag();
                            $("#tagid"+id).remove();
                        },
                        error: function(result) {
                            var errors = result.responseJSON;
                            console.log(errors);

                          swal({
                            type: 'error',
                            title: 'Something went wrong, tag could not be deleted!'
                          });
                        }
                    });

                })
            });
        }

        initDeleteBtn();

        $("#actionTagBtn").on("click", function(e){
            var action = e.target.getAttribute('data-action');
            if(action == 'create'){
                $.ajax("{{route('backend.blog.tags.create')}}", {
                    method: 'POST',
                    dataType:'json',
                    data: {
                        title: $("#title").val(),
                        description: $("#description").val(),
                        _token: Laravel.csrfToken
                    },
                    success: function(result) {
                        $("#title-form-group").removeClass("has-error");
                        $("#title-feedback-block").hide();
                        $("#title-feedback").html('');
                        console.log('test');
                        var markup = `<tr id="tagid`+result.tag.id+`"><td class="tag" data-id="`+result.tag.id+`" data-url="`+result.url+`" style="color: #3c8dbc; cursor: pointer;">`+$("#title").val()+`</td><td> <button id="deleteBtn-`+result.tag.id+`" class="btn btn-xs btn-danger" type="submit"><i class="fa fa-trash"></i> Delete</button> </td></tr>`;

                        $("#tagsList").append(markup);

                        setTimeout(initDeleteBtn, 100);
                        setTimeout(initTags, 100);

                        $("#title").val('');
                        $("#description").val('');

                        $("#tag-feedback-text").text("Successfully added new tag.");
                        $("#tag-feedback-info").show().delay(5000).fadeOut();

                    },
                    error: function(result) {
                        var errors = result.responseJSON;
                        if(typeof errors.title !== 'undefined'){

                            $("#title-form-group").addClass("has-error");
                            $("#title-feedback-block").show();
                            $("#title-feedback").html(errors.title[0]);
                        } else if(errors.message != null) {
                            $("#title-form-group").addClass("has-error");
                            $("#title-feedback-block").show();
                            $("#title-feedback").html(errors.message);
                        }
                    }
                });
            } else if (action == 'update') {
                $.ajax("{{route('backend.blog.tags.update')}}", {
                    method: 'POST',
                    dataType:'json',
                    data: {
                        id: $("#id").val(),
                        title: $("#title").val(),
                        slug: $('#slug').val(),
                        description: $("#description").val(),
                        _token: Laravel.csrfToken
                    },
                    success: function(result) {
                        $("#title-form-group").removeClass("has-error");
                        $("#title-feedback-block").hide();
                        $("#title-feedback").html('');

                        $("#tag-feedback-text").text("Successfully updated tag.");
                        $("#tag-feedback-info").show().delay(5000).fadeOut();
                        $('#tagid'+result.tag.id).find('.tag').html(result.tag.title);
                        $('#slug').val(result.tag.slug);
                    },
                    error: function(result) {
                        var errors = result.responseJSON;
                        if(typeof errors !== 'undefined') {
                            if(typeof errors.title !== 'undefined'){
                                $("#title-form-group").addClass("has-error");
                                $("#title-feedback-block").show();
                                $("#title-feedback").html(errors.title[0]);
                            } else if(typeof errors.message !== 'undefined') {
                                $("#title-form-group").addClass("has-error");
                                $("#title-feedback-block").show();
                                $("#title-feedback").html(errors.message);
                            }
                        }
                    }
                });
            }
        });


    });
</script>
@endpush
