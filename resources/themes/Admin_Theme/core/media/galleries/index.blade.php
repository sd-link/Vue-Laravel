@extends('layouts.admin')

@section('content')
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                  <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Galleries</h3>
                        <div class="box-tools">
                            <a href="{{ route('backend.media.galleries.create') }}"><button class="btn btn-block btn-primary btn-sm" type="submit">Create New</button></a>
                        </div>
                    </div>
                    <div class="box-body">
                        {{-- <div id="counts">
                            @component('components.content.counts', [
                                'indexurl' => route('backend.media.galleries.index'),
                                'content' => $content
                            ])
                            @endcomponent
                        </div> --}}
                        <div style="margin-bottom: 4px;">
                            {{-- @component('components.content.search', [
                                'select_options' => [
                                    0 =>['value'=>'title', 'text'=>'Title'],
                                    1 =>['value'=>'username', 'text'=>'Username']
                                ],
                            ])
                            @endcomponent --}}
                            {{-- <div id="pagination" style="border: 0px blue solid; height: 24px;" class="pull-right">{{ $galleries->links() }}</div>
                            <div style="clear: both;"></div> --}}
                        </div>
                        <div id="content">
                            <gallery-index v-bind="{!! $settings !!}"></gallery-index>
                        </div>
                    </div>
                    <!-- /.box-body -->
                  </div>
                </div>
            </div>
        </section>
@endsection

@push('scripts')
    <script src="{{ asset('js/gallery_index.js') }}?{{ str_random(7) }}"></script>
    {{-- <script>
        var search = '{{$search}}' || '';
        var filter = '{{$filter}}' || 'title';
    </script>
    <script src="{{asset('js/search.js')}}"></script> --}}
@endpush
