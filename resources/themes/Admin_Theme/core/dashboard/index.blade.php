@extends('layouts.admin')

@section('content')
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Dashboard</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                  <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Blog Posts</h3>
                    </div>

                    {{-- @include('admin.tags.message') --}}
                    <!-- /.box-header -->
                    <div class="box-body">
                        content here
                    </div>
                    <!-- /.box-body -->
                  </div>
            </div>
        </section>
@endsection
