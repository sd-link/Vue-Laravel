@component('components.modal',['id' => 'quickEditModal', 'class' => 'modal-dialog', 'title' => 'Edit Image'])
    @slot('content')

        @component('components.image.quickedit', [
            'saveurl' => route('backend.media.images.update'),
            'show_tags' => true,
            'tags' => $tags
        ])
            @slot('settings')
                @include('core.media.galleries.shared.settings')
            @endslot
        @endcomponent

    @endslot

    @slot('footer')
        <button id="editBtn" type="button" class="btn btn-primary pull-right">Save</button>
    @endslot

@endcomponent
