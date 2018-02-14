
    @component('components.modal',['id' => 'quickEditModal', 'class' => 'modal-dialog', 'title' => 'Quick Edit Post'])
        @slot('content')

            @component('components.content.quickedit', [
                'saveurl' => route('backend.blog.update'),
                'show_slug' => true,
                'show_access' => true,
                'show_categories' => true,
                'show_tags' => true,
                'categories' => $categories,
                'tags' => $tags
            ])
            @endcomponent

        @endslot

        @slot('footer')
            <button id="editBtn" type="button" class="btn btn-primary pull-right">Save</button>
        @endslot

    @endcomponent


@push('scripts')

    {{-- <script src="{{asset('js/init_simplemde.js')}}"></script>
    <script>

    var simplemde = null;
    var clickedContent = null;

    $('#quickEditPostModal').on('hidden.bs.modal', function () {
        if(simplemde != null) {
            simplemde.toTextArea();
            simplemde = null;
        }
        $("#categories").select2('destroy');
        $("#tags").select2('destroy');
        $('.content-tab-nav').removeClass('active');
        $('.basic-tab-nav').addClass('active');

        $('#tabBasic').addClass('active');
        $('#tabContent').removeClass('active');
    })

    $('.content-tab-nav').unbind();
    $('.content-tab-nav').on('shown.bs.tab', 'a[data-toggle="tab"]', function (e) {

        if(simplemde != null) {
            simplemde.toTextArea();
            simplemde = null;
        }

        console.log('we here')

        simplemde = initSimpleMde("content_editor");

        simplemde.codemirror.on('refresh', function() {
            if (simplemde.isFullscreenActive()) {
                $('body').addClass('simplemde-fullscreen');
            } else {
                $('body').removeClass('simplemde-fullscreen');
            }
        });

        var doc = simplemde.codemirror.getDoc();
        doc.setValue(content_body);
    })

    // Quick Edit Post Modal
    $('#quickEditPostModal').on('show.bs.modal', function(e) {
        var $modal = $(this);
        var id = e.relatedTarget.getAttribute('data-id');
        clickedContent = $('#row'+id);
        var status = clickedContent.find('.status_column').attr('data-status');
        var access = clickedContent.find('.status_column').attr('data-access');
        var slug = clickedContent.find('.post_slug').attr('data-slug');
        var categories = clickedContent.find('.category_id').html();
        var post_title = clickedContent.find('.post_title').text();
        var content_body = clickedContent.find('.content_body').text();

        console.log('column_body: ' + content_body)
        $('#quickEditPostForm').find('.post_id').val(id);
        $('#quickEditPostForm').find('.post_title').val(post_title);
        $('#quickEditPostForm').find('.post_editor').val(content_body);
        $('#quickEditPostForm').find('.post_status').val(status);
        $('#quickEditPostForm').find('.post_access').val(access);
        $('#quickEditPostForm').find('.post_slug').val(slug);



        // Initiate categories
        var selectedCats = [];
        $.each( $('#row'+id).find('.category_id'), function( key ) {
            console.log('cats: ' + $(this).attr('data-category'));
            selectedCats.push($(this).attr('data-category'));
        });

        $('#categories').select2().val(selectedCats).trigger("change");
        $('#categories').select2({
            tags: true,
            width: '100%',
            templateResult: function (data) {
                if (!data.element) { return data.text; }
                var $element = $(data.element);
                var $wrapper = $('<div></div>');
                $wrapper.addClass($element[0].className);
                $wrapper.text(data.text);
                return $wrapper;
            }
        });

        // Initiate tags
        var selectedTags = [];
        $.each( $('#row'+id).find('.tag-id'), function( key ) {
            console.log('tags: ' + $(this).attr('data-tag'));
            selectedTags.push($(this).attr('data-tag'));
        });

        $('#tags').select2().val(selectedTags).trigger("change");
        $('#tags').select2({
            tags: true,
            width: '100%'
        });
    });

    $("#quickEditPostBtn").on("click", function(e){
        // console.log('post: '+$('#quickEditPostForm').find('.post_id').val());

        $('#quick-edit-post-feedback-info').hide();
        $('#updatingAnimation').show();
        // console.log('slug: ' + $('#quickEditPostForm').find('.post_slug').val());
        var content = null;

        if(simplemde) {
            content = simplemde.value();
        }

        if(content == null)
        {
            content = clickedContent.find('.content_body').text();
        }

        $.ajax('{{route('backend.blog.quickupdate')}}', {
            method: 'POST',
            dataType:'json',
            data: {
                id: $('#quickEditPostForm').find('.post_id').val(),
                title: $('#quickEditPostForm').find('.post_title').val(),
                content: content,
                slug: $('#quickEditPostForm').find('.post_slug').val(),
                status: $('#quickEditPostForm').find('.post_status').val(),
                access: $('#quickEditPostForm').find('.post_access').val(),
                tags: $('#quickEditPostForm').find('#tags').val(),
                categories: $('#quickEditPostForm').find('#categories').val(),
                _token: Laravel.csrfToken
            },

            success: function(result) {
                $('#updatingAnimation').hide();
                location.reload();
            },
            error: function(result) {
                var errors = result.responseJSON;

                $('#tabBasic').find('.title-group').removeClass('has-error');
                $('#tabBasic').find('.title-feedback').html('');

                $('#updatingAnimation').hide();
                $('#quick-edit-post-error-feedback-info').hide();
                $('#quick-edit-post-error-feedback-info').html('');

                if(typeof errors.message !== 'undefined'){
                    $('#quick-edit-post-error-feedback-info').show();
                    $('#quick-edit-post-error-feedback-info').html(errors.message);
                }

                if(typeof errors.title !== 'undefined'){
                    $('#tabBasic').find('.title-group').addClass('has-error');
                    $('#tabBasic').find('.title-feedback').html(errors.title);
                }
            }
        });
    }); --}}
  </script>
@endpush
