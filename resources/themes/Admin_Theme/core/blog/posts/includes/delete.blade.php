@component('components.delete',[
    'title' => 'Delete Post',
    'page' => $page,
    'contenturl' => route('backend.blog.index'),
    'deleteurl' => route('backend.blog.delete')])
    @slot('content')
        <div style="margin: 20px 10px;">
           Do you want to delete <span id="contentTitle" style="color: rgb(60, 141, 188); font-weight: bold;"></span>?
        </div>
    @endslot
@endcomponent
