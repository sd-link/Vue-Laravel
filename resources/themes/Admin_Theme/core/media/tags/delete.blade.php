
    @component('components.taxonomy.delete',['title' => 'Delete Tag', 'page' => null, 'deleteurl' => $url])
        @slot('content')
            <div style="margin: 20px 10px;">
               Do you want to delete this <span id="contentTitle" style="color: rgb(60, 141, 188); font-weight: bold;"></span> tag?
            </div>
        @endslot
    @endcomponent
