@extends('layouts.admin')

@section('content')

        @include('partials/breadcrumb')

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                  <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Categories</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="categoriesTable" class="table table-content">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="categoriesList">
                                @foreach ($categories as $category)
                                    <tr id="categoryid{{ $category->id }}" data-parent="true" @if($category->children->count())data-children="true"@else()data-children="false"@endif>
                                        <td><div id="title{{$category->id}}" class="parent category" @if($category->children->count())data-children="true"@else()data-children="false"@endif data-id="{{ $category->id }}" data-url="{{ route('backend.blog.categories.get', $category->id)}}" style="color: #3c8dbc; cursor: pointer;">{{$category->title}}</div></td>
                                        <td style="width: 150px;">
                                            <button id="deleteBtn-{{ $category->id }}" class="btn btn-xs btn-danger" type="submit"><i class="fa fa-trash"></i> Delete</button>
                                        </td>
                                    </tr>

                                    @foreach ($category->children as $child)
                                        <tr id="categoryid{{ $child->id }}" data-parent="false">
                                            <td><div class="child category" data-id="{{ $child->id }}" data-parent="{{$category->id}}" data-url="{{ route('backend.blog.categories.get', $child->id)}}" style="padding-left: 20px; color: #3c8dbc; cursor: pointer;">{{$child->title}}</div></td>
                                            <td style="width: 150px;">
                                                <button id="deleteBtn-{{ $child->id }}" class="btn btn-xs btn-danger" type="submit"><i class="fa fa-trash"></i> Delete</button>
                                            </td>
                                        </tr>
                                    @endforeach

                                @endforeach

                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Title</th>
                                    <th>Actions</th>
                                </tr>
                            </tfoot>
                        </table>
                        {{ $categories->links() }}
                    </div>
                    <!-- /.box-body -->
                  </div>
                </div>

                <div class="col-xs-6">
                  <div class="box">
                    <div class="box-header">
                        <h3 id="actionTitle" class="box-title">Add categories</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <form id="addCategoryForm" action="">
                            <div id="title-form-group" class="form-group">
                                {{ csrf_field() }}
                                <input type="hidden" id="id" name="id" value="">
                                <input type="hidden" id="url" name="url" value="">
                                <label for="title">Title</label>
                                <input type="text" placeholder="Enter category title" value="" id="title" name="title" class="form-control">
                                <span id="title-feedback-block" class="help-block" style="display: none;">
                                    <strong id="title-feedback"></strong>
                                </span>
                            </div>
                            <div id="parent-form-group" class="form-group">
                                <label for="name">Parent</label>
                                <select class="form-control" name="parent" id="parent">
                                    <option value="0">No Parent</option>
                                    @foreach($categories as $parent)
                                        <option value="{{$parent->id}}">{{$parent->title}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="title">Description</label> <small>(optional)</small>
                                <textarea id="description" name="description" class="form-control" placeholder="Enter category description"></textarea>
                            </div>
                        </form>

                    </div>

                    <div class="box-footer">
                        <button id="actionCategoryBtn" data-action="create" class="btn btn-primary pull-left" type="submit">Add Category</button>
                        <button id="resetCategoryBtn" class="btn btn-primary pull-right" type="submit" style="display: none">Back To Add</button>
                    </div>
                    <!-- /.box-body -->
                  </div>

                        <div id="category-feedback-info" class="callout callout-info" style="display: none;">
                            <p id="category-feedback-text"></p>
                        </div>

                </div>
            </div>
        </section>
@endsection

@push('scripts')
<script type="text/javascript">
    $(document).ready(function() {

        function initDeleteBtn() {
            $('[id^=deleteBtn-]').unbind();
            $('[id^=deleteBtn-]').on("click", function(event){
                console.log('funny');
                event.preventDefault();
                var id = event.target.id.slice(10);
                swal({
                    title: 'Wait!',
                    text: "Are you sure you want to delete this category and it's children?",
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Delete',
                    allowOutsideClick: false,
                    confirmButtonClass: 'btn btn-success',
                    cancelButtonClass: 'btn btn-danger'
                }).then(function() {
                    $.ajax("{{route('backend.blog.categories.delete')}}", {
                        method: 'POST',
                        dataType:'json',
                        data: {
                            id: id,
                            _token: Laravel.csrfToken
                        },
                        success: function(result) {
                            console.log('category deleted');
                            $("#categoryid"+id).remove();

                            $('.child').each(function(i,child){
                                if(id == child.getAttribute("data-parent")){
                                    // console.log("child parent: " + );
                                    child.closest('tr').remove();
                                }
                            });
                            resetToAddCategory();

                        },
                        error: function(result) {
                            var errors = result.responseJSON;
                            console.log(errors);
                            if(typeof errors !== 'undefined') {
                                swal({
                                    type: 'error',
                                    title: errors.message
                                });
                            } else {
                                swal({
                                    type: 'error',
                                    title: 'Something went wrong, category coult not be deleted!'
                                });
                            }
                        }
                    });

                })
            });
        }

        initDeleteBtn();

        function initCategories() {
            console.log('init categories');
            $('.category').unbind();
            $('.category').click(function(event) {
                var id = event.target.getAttribute('data-id');
                var url = event.target.getAttribute('data-url');
                var children = event.target.getAttribute('data-children');

                $.ajax(url, {
                    method: 'GET',
                    dataType:'json',
                    success: function(result) {
                        console.log("children: " + children);
                        if(children === 'true'){
                            $('#parent').empty();
                            $('#parent-form-group').hide();

                        } else {
                            getParents(result.parent, id);
                            $('#parent-form-group').show();
                        }

                        $("#actionTitle").html("Update Category");
                        $("#title").val(result.title);
                        $("#description").val(result.description);
                        $("#id").val(result.id);
                        $("#url").val(url);
                        // if(result.parent == null) {
                        //     $("#parent").val(0);
                        //     console.log('SET PARENT to ZERO');
                        // } else {
                        //     $("#parent").val(result.parent);
                        //     console.log('SET PARENT TO '+result.parent);
                        // }

                        $("#actionCategoryBtn").html("Update Category");
                        $("#actionCategoryBtn").attr("data-action", "update");
                        $("#resetCategoryBtn").show();
                    },
                    error: function(result) {
                        alert('error');
                    }
                });
            });
        }

        initCategories();

        function getParents(selectedParent, exclude) {
            $.ajax("{{route("backend.blog.categories.getparents")}}", {
                method: 'GET',
                dataType:'json',
                success: function(result) {
                    $('#parent').empty();
                    var parentCategories = "";
                    parentCategories += '<option value="0">No Parent</option>';
                    $.each( result.categories, function( key, value ) {
                        if(exclude != value.id){
                            parentCategories += '<option value=' + value.id + '>' + value.title + '</option>';
                        }
                    });

                    $('#parent').append(parentCategories);

                    if(selectedParent == null || selectedParent == 0) {
                        $("#parent").val(0);
                        // console.log("PARENT ZERO");
                    } else {
                        $("#parent").val(selectedParent);
                        // console.log("PARENT NOT ZERO " + parent);
                    }
                },
                error: function(result) {
                    alert('error gettings parents');
                }
            });
        }

        $('#resetCategoryBtn').click(function(event) {
            // $('#parent').find('option[value=0]').text('No Parent');
            resetToAddCategory();
        });


        function resetToAddCategory() {
            getParents(0, 0);
            $('#parent-form-group').show();
            $("#actionTitle").html("Add Category");
            $("#id").val('');
            $("#title").val('');
            $("#description").val('');
            $("#parent").val(0);
            $("#actionCategoryBtn").attr("data-action", "create");
            $("#actionCategoryBtn").html("Add Category");
            $("#resetCategoryBtn").hide();
        }

        $("#actionCategoryBtn").on("click", function(e){
            var action = e.target.getAttribute('data-action');
            if(action == 'create'){
                $.ajax("{{route('backend.blog.categories.create')}}", {
                    method: 'POST',
                    dataType:'json',
                    data: {
                        title: $("#title").val(),
                        description: $("#description").val(),
                        parent: $("#parent").val(),
                        _token: Laravel.csrfToken
                    },
                    success: function(result) {
                            $("#title-form-group").removeClass("has-error");
                            $("#title-feedback-block").hide();
                            $("#title-feedback").html('');

                        var markup = `<tr id="categoryid-`+result.id+`"><td><a href="555">`+$("#title").val()+`</a></td><td> <button id="deleteBtn-`+result.id+`" class="btn btn-xs btn-danger" type="submit"><i class="fa fa-trash"></i> Delete</button> </td></tr>`;

                        if($("#parent").val() != 0){
                            var markup = `<tr id="categoryid`+result.id+`" data-parent="false"><td><div class="child category" data-id=`+result.id+` data-parent=`+$("#parent").val()+` data-url=`+result.url+` style="padding-left: 20px; color: #3c8dbc; cursor: pointer;">`+$("#title").val()+`</div></td><td><button id="deleteBtn-`+result.id+`" class="btn btn-xs btn-danger" type="submit"><i class="fa fa-trash"></i> Delete</button> </td></tr>`;

                            $("#categoryid"+$("#parent").val()).after(markup);
                        } else {
                            var markup = `<tr id="categoryid`+result.id+`" data-parent="false"><td><div class="category" data-id=`+result.id+` data-url=`+result.url+` style="color: #3c8dbc; cursor: pointer;">`+$("#title").val()+`</div></td><td><button id="deleteBtn-`+result.id+`" class="btn btn-xs btn-danger" type="submit"><i class="fa fa-trash"></i> Delete</button> </td></tr>`;
                            $("#categoriesList").append(markup);
                        }

                        getParents(0, 0);
                        setTimeout(initDeleteBtn, 100);
                        setTimeout(initCategories, 100);
                        $("#category-feedback-text").text("Successfully added new category.");
                        $("#category-feedback-info").show().delay(5000).fadeOut();
                        $("#title").val('');
                        $("#description").val('');
                        $("#parent").val("0");
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
            } else if (action == 'update') {
                var testing = $("#parent").val();
                console.log("update parent:" + $("#parent").val());
                $.ajax("{{route('backend.blog.categories.update')}}", {
                    method: 'POST',
                    dataType:'json',
                    data: {
                        id: $("#id").val(),
                        title: $("#title").val(),
                        description: $("#description").val(),
                        parent: $("#parent").val(),
                        _token: Laravel.csrfToken
                    },
                    success: function(result) {
                        $("#title-form-group").removeClass("has-error");
                        $("#title-feedback-block").hide();
                        $("#title-feedback").html('');

                        console.log('prev: '+result.previous)

                        if($("#parent").val() != 0 && $("#parent").val() != null){
                            $("#categoryid"+$("#id").val()).remove();
                            var markup = `<tr id="categoryid`+$("#id").val()+`" data-parent="false"><td><div class="child category" data-id=`+$("#id").val()+` data-parent=`+$("#parent").val()+` data-url=`+$("#url").val()+` style="padding-left: 20px; color: #3c8dbc; cursor: pointer;">`+$("#title").val()+`</div></td><td><button id="deleteBtn-`+result.id+`" class="btn btn-xs btn-danger" type="submit"><i class="fa fa-trash"></i> Delete</button> </td></tr>`;

                            $("#categoryid"+$("#parent").val()).after(markup);
                        } else {
                            $("#categoryid"+$("#id").val()).remove();
                            var children = $("#categoryid"+$("#id").val()).attr("data-children");
                            if(children) {
                                console.log('has children');
                                $("#title"+$("#id").val()).html($("#title").val());
                            }
                            else{
                                var categoryid = $("#id").val();
                                console.log('incomming id:'+categoryid)

                                var markup = `<tr id="categoryid`+$("#id").val()+`" data-parent="false"><td><div class="category" data-id=`+$("#id").val()+` data-url=`+$("#url").val()+` style="color: #3c8dbc; cursor: pointer;">`+$("#title").val()+`</div></td><td><button id="deleteBtn-`+result.id+`" class="btn btn-xs btn-danger" type="submit"><i class="fa fa-trash"></i> Delete</button> </td></tr>`;
                                if(categoryid > 1){
                                    $("#categoryid"+result.previous).after(markup);
                                } else if (categoryid == 1) {
                                    $("#categoryid"+result.next).before(markup);
                                }
                                else {
                                    $("#categoriesList").append(markup);
                                }
                            }
                        }

                        setTimeout(initDeleteBtn, 100);
                        setTimeout(initCategories, 100);

                        getParents($("#parent").val(), $("#id").val());
                        $("#category-feedback-text").text("Successfully updated category.");
                        $("#category-feedback-info").show().delay(5000).fadeOut();


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
