@extends('layouts.admin')

@section('content')
        @if (Session::has('page-managed'))
            <div id="page-feedback-info" class="admin-notification callout callout-info">
                <p id="page-feedback-text"></p>
            </div>
        @endif

        <!-- Main content -->
        <section class="content {{ SiteSetting::getDeviceType() }}">
            <div class="row">
                <div class="col-xs-12">
                  <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Comments</h3>
                    </div>

                    {{-- @include('admin.tags.message') --}}
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div style="margin-bottom: 2px;">
                            <div id="counts">
                                @include('core.comments.counts')
                            </div>
                        </div>
                        <div style="margin-bottom: 4px;">
                            <div class="pull-left">
                                    <div style="display: inline-flex;">
                                        <div style="float: left; margin-right: 1px;"><input id="search" class="form-control" type="text" placeholder="Search" style="padding-left: 5px;"></div>
                                        <div style="float: left; margin-right: 1px;"><select id="filter" class="form-control">
                                            <option value="title">Name</option>
                                            <option value="username">Username</option>
                                        </select></div>
                                        <div id="searchBtn" style="float: left;"><button class="btn btn-primary">Search</button></div>
                                    </div>
                                </div>
                            <div id="pagination" style="border: 0px blue solid; height: 24px;" class="pull-right">{{ $comments->links() }}</div>
                            <div style="clear: both;"></div>
                        </div>

                        <table class="table table-content noselect">
                            <thead>
                                <tr>
                                    <th style="width: 138px;">Author</th>
                                    <th style="width: 530px;">Comment</th>
                                    <th style="width: 250px;">In Response To</th>
                                    <th style="width: 150px;">Submitted On</th>
                                </tr>
                            </thead>
                            <tbody id="comments">
                                @include('core.comments.comments')
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Author</th>
                                    <th>Comment</th>
                                    <th>In Response To</th>
                                    <th>Submitted On</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <!-- /.box-body -->
                  </div>
            </div>
        </section>
@endsection

@push('scripts')
    <script>
        var page = '{{ $page }}'
        var status = '{{ $status }}'

        initComments()

        function initComments()
        {
            $(".approve").on("click", function(e) {
                var clickedComment = e.target;
                changeStatus(clickedComment, {{ Comment::APPROVED }})
            });

            $(".unapprove").on("click", function(e) {
                var clickedComment = e.target;
                changeStatus(clickedComment, {{ Comment::PENDING }})
            });

            $(".spam").on("click", function(e) {
                var clickedComment = e.target;
                changeStatus(clickedComment, {{ Comment::SPAM }})
            });

            $(".not-spam").on("click", function(e) {
                var clickedComment = e.target;
                changeStatus(clickedComment, {{ Comment::APPROVED }})
            });

            $(".delete").on("click", function(e) {
                var clickedComment = e.target;
                deleteComment(clickedComment)
            });
        }

        function getComments()
        {
            $.ajax('{{route('backend.comments.index')}}', {
                method: 'get',
                dataType:'json',
                data: {
                    page: page,
                    status: status,
                },
                success: function(result) {
                    $('#comments').html(result.comments)
                    $('#counts').html(result.counts)
                    $('#pagination').html(result.pagination)
                    initComments()

                    if($("#no-comments").length != 0) {
                        oldPage = page
                        page = page - 1

                        if(page < 1)
                            page = 1

                        console.log('page: ' + page)

                        if(oldPage > 1)
                            getComments()
                    }
                    console.log('getcomments')
                },
                error: function(result) {
                    // enableBtn(saveBtn, 'Save');
                    var errors = result.responseJSON;
                }
            });
        }

        function deleteComment(clickedComment)
        {
            $.ajax('{{route('backend.comments.delete')}}', {
                method: 'POST',
                dataType:'json',
                data: {
                    comment_id: $(clickedComment).data('comment-id'),
                    _token: Laravel.csrfToken
                },
                success: function(result) {
                    getComments()
                },
                error: function(result) {
                    var errors = result.responseJSON;
                }
            });
        }

        function changeStatus(clickedComment, status) {
            $.ajax('{{route('backend.comments.status')}}', {
                method: 'POST',
                dataType:'json',
                data: {
                    comment_id: $(clickedComment).data('comment-id'),
                    status: status,
                    _token: Laravel.csrfToken
                },
                success: function(result) {
                    getComments()
                },
                error: function(result) {
                    var errors = result.responseJSON;
                }
            });
        }
    </script>
@endpush
