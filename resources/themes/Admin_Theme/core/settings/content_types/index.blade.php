@extends('layouts.admin')

@section('content')
        {{-- @include('partials/breadcrumb') --}}

        <!-- Main content -->
        <section class="content {{ SiteSetting::getDeviceType() }}">
            <div class="row">
                <div class="col-xs-12">
                  <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Content Types</h3>
                        <div class="box-tools">
                            <a href="{{ route('backend.content.types.create')}}">
                                <button class="btn btn-block btn-primary btn-sm" type="submit"><i class="fa fa-fw fa-plus"></i> Create</button>
                            </a>
                        </div>
                    </div>
                    <div class="box-body">
                        <div style="margin-bottom: 4px;">
                            <div id="pagination" style="border: 0px blue solid; height: 24px;" class="pull-right">{{ $contentType->links() }}</div>
                            <div style="clear: both;"></div>
                        </div>
                        <div id="content">
                            @component('components.content.content', [
                                'class' => 'list blog',
                            ])
                                @slot('content')
                                    @foreach ($contentType as $type)
                                        <div class="row">
                                            {{-- TITLE --}}
                                            <div class="column title_column">
                                                <a href="{{ route('backend.content.types.edit', $type->id) }}" class="content_title">{{$type->name}}</a>
                                            </div>
                                        </div>
                                    @endforeach
                                @endslot
                            @endcomponent
                        </div>
                    </div>
                  </div>
                </div>
            </div>
        </section>
@endsection
