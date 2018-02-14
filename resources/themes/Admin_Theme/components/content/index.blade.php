

    <section class="content {{ Setting::getDeviceType() }}">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Blog Posts</h3>
                        <div class="box-tools">
                            <a href="{{ route('backend.blog.create')}}"><button class="btn btn-block btn-primary btn-sm" type="submit" value="Add New"><i class="fa fa-fw fa-plus"></i> Add New</button></a>
                        </div>
                    </div>

                    {{-- @include('admin.tags.message') --}}
                    <div class="box-body">
                        <div id="counts">
                            @include('core.blog.posts.includes.counts')
                        </div>
                        <div style="margin-bottom: 4px;">
                            @component('components.content.search', [
                                'select_options' => [
                                    0 =>['value'=>'title', 'text'=>'Title'],
                                    1 =>['value'=>'username', 'text'=>'Username'],
                                    2 =>['value'=>'category', 'text'=>'Category'],
                                    3 =>['value'=>'tag', 'text'=>'Tag']
                                ],
                            ])
                            @endcomponent
                            <div id="pagination" style="border: 0px blue solid; height: 24px;" class="pull-right">{{ $posts->links() }}</div>
                            <div style="clear: both;"></div>
                        </div>
                        <div id="content">
                            @include('core.blog.posts.includes.content')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
