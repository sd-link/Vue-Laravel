@extends('layouts.admin')

@section('content')
        @if (Session::has('user-managed'))
            <div id="user-feedback-info" class="admin-notification callout callout-info">
                <p id="user-feedback-text"></p>
            </div>
        @endif

        <!-- Content Header (Page header) -->
        {{-- @include('partials/breadcrumb') --}}

        @include('core.users.create')
        @include('core.users.edit')
        @include('core.users.delete')

        <!-- Main content -->
        <section class="content {{ SiteSetting::getDeviceType() }}">
            <div class="row">
                <div class="col-xs-12">
                  <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Users</h3>
                        <div class="box-tools">
                            <button id="createBtn" class="btn btn-block btn-primary btn-sm" data-toggle="modal" data-target="#createUserModal" data-backdrop="static" data-keyboard="false" type="button"><i class="fa fa-fw fa-plus"></i> Add New</button>
                        </div>
                    </div>

                    <!-- /.box-header -->
                    <div class="box-body">
                        <div style="border: 0px red solid; margin-bottom: 2px;">
                            <div style="border: 0px green solid;">
                                <div class="pull-right"><small>Found ({{$returnedUsersCount}})</small></div>
                                <div style="clear: both;"></div>
                            </div>
                        </div>
                        <div style="border: 0px red solid; margin-bottom: 4px;">
                            <div style="border: 0px green solid;" class="pull-left">
                                <div style="display: inline-flex;">
                                    <div style="float: left; margin-right: 1px;"><input id="search" type="text" placeholder="Search" style="padding-left: 5px; height: 25px;"></div>
                                    <div style="float: left; margin-right: 1px;"><select id="filter" style="height: 25px;">
                                        <option value="name">Name</option>
                                        <option value="username">Username</option>
                                        <option value="email">Email</option>
                                        <option value="role">Role</option>
                                    </select></div>
                                    <div id="searchBtn" style="float: left"><button style="height: 25px;">Search</button></div>
                                </div>
                            </div>

                            <div style="border: 0px blue solid; height: 24px;" class="pull-right">{{ $users->links() }}</div>
                            <div style="clear: both;"></div>
                        </div>

                        <table class="table table-content">
                            <thead>
                                <tr>
                                    <th id="userIdSort" class="tablesorting noselect">Id <i class="fa fa-sort-numeric-asc table-sorting-icon " aria-hidden="true"></i></th>
                                    <th id="userFirstNameSort" class="tablesorting noselect">Name <i class="fa fa-sort-alpha-asc table-sorting-icon" aria-hidden="true"></i></th>
                                    <th id="usernameSort" class="tablesorting noselect">Username <i class="fa fa-sort-alpha-asc table-sorting-icon" aria-hidden="true"></i></th>
                                    <th id="userEmailSort" class="tablesorting noselect">Email <i class="fa fa-sort-alpha-asc table-sorting-icon" aria-hidden="true"></i></th>
                                    <th class="noselect">Roles</th>
                                    <th>Extra Permissions</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                <tr id="row{{$user->id}}">

                                    <td class="id" style="width: 45px;">
                                        {{ $user->id }}
                                    </td>

                                    <td class="name" style="width: 170px;">
                                        {{ $user->firstname }} {{ $user->lastname }}
                                    </td>

                                    <td class="username" style="width: 170px;">
                                        {{ $user->username }}
                                    </td>

                                    <td class="email" style="width: 190px;">
                                        {{ $user->email }}
                                    </td>

                                    <td class="roles" style="width: 200px;">
                                        @foreach ($user->roles as $role)
                                            {{ $role->display_name }}@if(!$loop->last),@endif
                                        @endforeach
                                    </td>

                                    <td class="permissions">
                                        @foreach ($user->permissions as $permission)
                                            {{ $permission->display_name }}@if(!$loop->last),@endif
                                        @endforeach
                                    </td>

                                    <td style="width: 250px;">
                                        <form role="form" id="deleteForm-{{ $user->id }}" method="post">
                                            <input type="hidden" value="{{ $user->id }}" id="id" name="id" class="">
                                            {{ csrf_field() }}

                                            <button id="editBtn-{{ $user->id }}" class="btn btn-xs btn-default" data-url="{{ route('backend.users.edit', $user->id) }}" data-toggle="modal" data-target="#editUserModal" data-backdrop="static" data-keyboard="false" type="button"><i class="fa fa-edit"></i> Edit</button>
                                            <button id="deleteBtn-{{ $user->id }}" class="btn btn-xs btn-danger" data-id="{{ $user->id }}" data-toggle="modal" data-target="#deleteUserModal" data-backdrop="static" data-keyboard="false" type="button"><i class="fa fa-trash"></i> Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Id</th>
                                    <th>Name</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Roles</th>
                                    <th>Extra Permissions</th>
                                    <th>Actions</th>
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.scrollbar/0.2.11/jquery.scrollbar.min.js"></script>

<script>

    @if (Session::has('user-managed'))
        $("#user-feedback-text").text("{{Session::get('user-managed')}}");
        $("#user-feedback-info").show().delay(4000).fadeOut();
    @endif

    jQuery('.scrollbar-inner').scrollbar();

    var queryParameters = {}, queryString = location.search.substring(1), re = /([^&=]+)=([^&]*)/g, map;

    var sorting = '{{$sorting}}' || 'id';
    var order = '{{$order}}' || 'asc';

    $("#searchBtn").on("click", function(e){
        performSearch();
    });

    $('#search').keyup(function(e){
        if(e.keyCode == 13)
        {
            performSearch();
        }
    });

    function performSearch()
    {
        var search = $('#search').val();
        var filter = $('#filter').val();

        search = $.trim(search);
        filter = $.trim(filter);
        queryParameters['search'] = search.replace(/ /g, "+");
        queryParameters['filter'] = filter.replace(/ /g, "+");
        query = parametrize(queryParameters);

        location.search = query;
    }

    var search = '{{$search}}' || '';
    var filter = '{{$filter}}' || 'name';

    function initSearchFilter() {
        $('#search').val(search);
        $('#filter').val(filter);
    }

    initSearchFilter();


    $("#userIdSort").on("click", function(e){
        performSort('id');
    });

    $("#userFirstNameSort").on("click", function(e){
        performSort('name');
    });

    $("#usernameSort").on("click", function(e){
        performSort('username');
    });

    $("#userEmailSort").on("click", function(e){
        performSort('email');
    });

    function initSortingIcons() {
        var type = "asc";
        if(order == 'asc') {
            type= 'asc';
        }
        else {
            type = 'desc';
        }

        switch(sorting) {
            case 'id':
                $('#userIdSort').find('i').removeClass('fa-sort-numeric-asc');
                $('#userIdSort').find('i').removeClass('fa-sort-numeric-desc');
                $('#userIdSort').find('i').addClass('fa-sort-numeric-'+type);
                $('#userIdSort').addClass('tablesorting-active');
                break;

            case 'name':
                $('#userFirstNameSort').find('i').removeClass('fa-sort-alpha-asc');
                $('#userFirstNameSort').find('i').removeClass('fa-sort-alpha-desc');
                $('#userFirstNameSort').find('i').addClass('fa-sort-alpha-'+type);
                $('#userFirstNameSort').addClass('tablesorting-active');
                break;

            case 'username':
                $('#usernameSort').find('i').removeClass('fa-sort-alpha-asc');
                $('#usernameSort').find('i').removeClass('fa-sort-alpha-desc');
                $('#usernameSort').find('i').addClass('fa-sort-alpha-'+type);
                $('#usernameSort').addClass('tablesorting-active');
                break;

            case 'email':
                $('#userEmailSort').find('i').removeClass('fa-sort-alpha-asc');
                $('#userEmailSort').find('i').removeClass('fa-sort-alpha-desc');
                $('#userEmailSort').find('i').addClass('fa-sort-alpha-'+type);
                $('#userEmailSort').addClass('tablesorting-active');
                break;
        }
    }

    initSortingIcons();

    // Creates a mapap with the query string parameters
    while (m = re.exec(queryString)) {
        queryParameters[decodeURIComponent(m[1])] = decodeURIComponent(m[2]);
    }

    function parametrize(queryParameters) {
        var queryArray = new Array();

        for (key in queryParameters) {
            if(queryParameters[key] != null) {
                queryArray.push(key + '=' + queryParameters[key]);
            }
        }

        return queryArray.join('&');
    }

    function performSort(sort){
        type = "asc";

        if(sorting == sort) {
            if(order == 'asc') {
                type= 'desc';
            }
            else {
                type = 'asc';
            }
        }

        type = $.trim(type);
        sort = $.trim(sort);
        queryParameters['sorting'] = sort.replace(/ /g, "+");
        queryParameters['order'] = type.replace(/ /g, "+");
        query = parametrize(queryParameters);

        location.search = query;
    }

    // Delete User Modal
    $('#deleteUserModal').on('show.bs.modal', function(e) {

        var $modal = $(this);
        var id = e.relatedTarget.getAttribute('data-id');
        $('#deleteUserForm').find('.userid').val(id);

        var name = $('#row'+id).find('.name').html();
        var username = $('#row'+id).find('.username').html();

        console.log('username: '+ $.trim(name));

        var userToDeleteUsername = $.trim(username);
        var userToDeleteName = "";

        if($.trim(name) != '')
            userToDeleteName = 'with <i>name:</i> <strong>' + $.trim(name) + "</strong>";

        $('#userToDeleteUsername').html($.trim(userToDeleteUsername));
        $('#userToDeleteName').html($.trim(userToDeleteName));

        $('#delete-user-error-feedback-info').hide();
        $('#delete-user-error-feedback-info').html('');

        $.ajax('{{route('backend.users.crudpost')}}', {
            method: 'GET',
            dataType:'json',
            success: function(result) {
                var crudPostUsersMarkup = "";
                $.each( result.users, function( key, user ) {
                    if(user.id != id) {
                        crudPostUsersMarkup += `<option value="`+user.id+`">`+user.username+` - `+$.trim(user.firstname)+` `+$.trim(user.lastname)+`</option>`;
                    }
                });

                $('#assignPostsToUser').html(crudPostUsersMarkup);

            },
            error: function(result) {
                var errors = result.responseJSON;
            }
        });
    });

    $("#deleteUserBtn").on("click", function(e){
        console.log('user: '+$('#deleteUserForm').find('.userid').val());

        if (!$('#deleteUserForm').find('input[name=action]:checked').val()) {
           $('#delete-user-feedback-info').show();
           $('#delete-user-feedback-info').html('You need to select an option before submiting.');
       } else
        {
            $('#delete-user-feedback-info').hide();
            $('#deletingAnimation').show();

            $.ajax('{{route('backend.users.delete')}}', {
                method: 'POST',
                dataType:'json',
                data: {
                    id: $('#deleteUserForm').find('.userid').val(),
                    action: $('#deleteUserForm').find('input[name=action]:checked').val(),
                    transfer_to: $('#assignPostsToUser').val(),
                    _token: Laravel.csrfToken
                },

                success: function(result) {
                    $('#deletingAnimation').hide();
                    location.reload();
                },
                error: function(result) {
                    var errors = result.responseJSON;
                    $('#deletingAnimation').hide();
                    $('#delete-user-error-feedback-info').show();
                    $('#delete-user-error-feedback-info').html(errors.message);
                }
            });
        }

    });

</script>
@endpush
