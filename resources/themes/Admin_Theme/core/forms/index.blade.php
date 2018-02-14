@extends('layouts.admin')

@section('content')
        <!-- Content Header (Page header) -->
        @include('partials/breadcrumb')

        <!-- Main content -->
        <section class="content {{ SiteSetting::getDeviceType() }}">
            <div class="row">
                <div class="col-xs-12">
                  <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Forms</h3>
                        <div class="box-tools">
                            <a href="{{ route('backend.forms.create')}}"><button class="btn btn-block btn-primary btn-sm" type="submit" value="Add New">
                                <i class="fa fa-fw fa-plus"></i> Add New</button></a>
                        </div>

                    </div>

                    {{-- @include('admin.tags.message') --}}
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div style="border: 0px red solid; margin-bottom: 2px;">
                            <div style="border: 0px green solid;">
                                <div class="pull-left">
                                    <a href="{{route('backend.forms.index')}}">All</a>(12)
                                </div>

                                <div class="pull-right">Found (5)</div>
                                <div style="clear: both;"></div>
                            </div>
                        </div>

                        <table class="table table-content">
                            <thead>
                                <tr>
                                    <th style="width: 400px;">Title</th>
                                    <th style="width: 290px;">Shortcode</th>
                                    <th style="width: 120px;">Created</th>
                                    <th style="width: 120px;">Updated</th>
                                    <th style="width: 50px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($forms as $form)
                                <tr id="row{{ $form->id }}">

                                    <td class="page_slug" data-slug="{{$form->slug}}">
                                        <a href="{{ route('backend.forms.edit', $form->id) }}" class="form_title">{{$form->title}}</a>
                                    </td>

                                    <td class="page_body">[form id={{$form->id}}]</td>

                                    <td>
                                        {{$form->created_at}}
                                    </td>

                                    <td>
                                        {{$form->updated_at}}
                                    </td>

                                    <td>
                                        <form role="form" id="deleteForm-{{ $form->id }}" method="post">
                                            <input type="hidden" value="{{ $form->id }}" id="page_id" name="page_id" class="">
                                            {{ csrf_field() }}
                                            <button id="deleteBtn-{{ $form->id }}" class="btn btn-xs btn-danger" data-id="{{ $form->id }}" data-toggle="modal" data-target="#deletePageModal" data-backdrop="static" data-keyboard="false" type="button"><i class="fa fa-trash"></i> Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Title</th>
                                    <th>Shortcode</th>
                                    <th>Created</th>
                                    <th>Updated</th>
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
    <script>

    </script>
@endpush
