@extends('layouts.admin')

@section('content')
        <!-- Content Header (Page header) -->
        {{-- <div>
            @include('partials/breadcrumb')
        </div> --}}

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                  <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Roles</h3>
                        <div class="box-tools">
                            <a href="{{ route('backend.users.create')}}"><button class="btn btn-block btn-primary btn-sm" type="submit" value="Add New"><i class="fa fa-fw fa-plus"></i> Add New</button></a>
                        </div>
                    </div>
                    <div class="box-body">
                        <div style="border: 0 red solid; margin-bottom: 4px;">
                            <div style="border: 0px blue solid; height: 24px;" class="pull-right">{{ $roles->links() }}</div>
                            <div style="clear: both;"></div>
                        </div>
                        <table class="table table-content">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Display Name</th>
                                    <th>Permissions</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($roles as $role)
                                <tr>
                                    <td style="width: 170px;">
                                        {{ $role->name }}
                                    </td>

                                    <td style="width: 170px;">
                                        {{ $role->display_name }}
                                    </td>

                                    <td>
                                        @foreach ($role->permissions as $permission)
                                            {{ $permission->name }}@if(!$loop->last),@endif
                                        @endforeach
                                    </td>

                                    <td style="width: 250px;">
                                        <form role="form" id="deleteForm-{{ $role->id }}" method="post">
                                            <input type="hidden" value="{{ $role->id }}" id="id" name="id" class="">
                                            {{ csrf_field() }}
                                            <button id="deleteBtn-{{ $role->id }}" class="btn btn-xs btn-default" type="submit"><i class="fa fa-edit"></i> Edit</button>
                                            <button id="deleteBtn-{{ $role->id }}" class="btn btn-xs btn-danger" type="submit"><i class="fa fa-trash"></i> Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Name</th>
                                    <th>Display Name</th>
                                    <th>Permissions</th>
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
