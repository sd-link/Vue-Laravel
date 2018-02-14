@extends('layouts.admin')

@section('content')
        <!-- Content Header (Page header) -->
        @include('partials/breadcrumb')

        @include('core.media.images.quickedit')
        @component('components.delete',[
            'title' => 'Delete Image',
            'page' => $page,
            'contenturl' => route('backend.media.images.index'),
            'deleteurl' => route('backend.media.images.delete')])
            @slot('content')
                <div style="margin: 20px 10px;">
                   Do you want to delete this image <span id="contentTitle" style="color: rgb(60, 141, 188); font-weight: bold;"></span>?
                </div>
            @endslot
        @endcomponent
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                  <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Images</h3>
                        {{-- <div class="box-tools">
                            <a href="{{ route('backend.media.galleries.create')}}"><button class="btn btn-block btn-primary btn-sm" type="submit" value="Add New"><i class="fa fa-fw fa-plus"></i> Add New</button></a>
                        </div> --}}
                    </div>

                    <div class="box-body">
                        <div id="counts">
                            <div style="border: 0px red solid; margin-bottom: 2px;">
                                <div style="border: 0px green solid;">
                                    <div class="pull-left">
                                        <a href="{{route('backend.media.images.index')}}">All</a>
                                    </div>
                                    <div class="pull-right">Found ({{$returnedCount}})</div>
                                    <div style="clear: both;"></div>
                                </div>
                            </div>
                        </div>

                        <div style="margin-bottom: 4px;">
                            <div class="search_options pull-left">
                                    <div style="display: inline-flex;">
                                        <div style="float: left; margin-right: 1px;"><input class="form-control" id="search" type="text" placeholder="Search" style="padding-left: 5px;"></div>
                                        <div style="float: left; margin-right: 1px;"><select class="form-control" id="filter">
                                            <option value="title">Title</option>
                                            <option value="username">Username</option>
                                        </select></div>
                                        <div id="searchBtn" style="float: left;"><button class="btn btn-primary">Search</button></div>
                                    </div>
                                </div>
                            <div id="pagination" style="border: 0px blue solid; height: 24px;" class="pull-right">{{ $images->links() }}</div>
                            <div style="clear: both;"></div>
                        </div>
                        <div id="content">
                            @include('core.media.images.includes.content')
                        </div>
                    </div>
            </div>
        </section>
@endsection

@push('scripts')

<script>

    var queryParameters = {}, queryString = location.search.substring(1), re = /([^&=]+)=([^&]*)/g, map;

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
    var filter = '{{$filter}}' || 'title';

    function initSearchFilter() {
        $('#search').val(search);

        if(search != '' & filter == "title") {
            $.each( $('.gallery_title'), function( key ) {
                var html = $(this).html();
                var regexp = new RegExp(search, "gi");

                // Highlight the search string for user
                $(this).html(html.replace(regexp, '<strong>$&</strong>'));
            });
        }

        $('#filter').val(filter);
    }

    initSearchFilter();

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

</script>
@endpush
