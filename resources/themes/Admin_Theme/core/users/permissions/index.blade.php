@extends('layouts.admin')

@section('content')
        {{-- <div>
            @include('partials/breadcrumb')
        </div> --}}

        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                  <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Permissions</h3>
                        <div class="box-tools">
                            <a href="{{ route('backend.users.create')}}"><button class="btn btn-block btn-primary btn-sm" type="submit" value="Add New"><i class="fa fa-fw fa-plus"></i> Add New</button></a>
                        </div>
                    </div>
                    <div class="box-body">
                        <div style="border: 0 red solid; margin-bottom: 4px;">
                            <div style="border: 0px blue solid; height: 24px;" class="pull-right">{{ $permissions->links() }}</div>
                            <div style="clear: both;"></div>
                        </div>
                        <table class="table table-content">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Display Name</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($permissions as $permission)
                                <tr>
                                    <td>
                                        {{ $permission->name }}
                                    </td>

                                    <td>
                                        {{ $permission->display_name }}
                                    </td>

                                    <td style="width: 190px;">
                                        <form role="form" id="deleteForm-{{ $permission->id }}" method="post">
                                            <input type="hidden" value="{{ $permission->id }}" id="id" name="id" class="">
                                            {{ csrf_field() }}
                                            <button id="deleteBtn-{{ $permission->id }}" class="btn btn-xs btn-default" type="submit"><i class="fa fa-edit"></i> Edit</button>
                                            <button id="deleteBtn-{{ $permission->id }}" class="btn btn-xs btn-danger" type="submit"><i class="fa fa-trash"></i> Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Name</th>
                                    <th>Display Name</th>
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
