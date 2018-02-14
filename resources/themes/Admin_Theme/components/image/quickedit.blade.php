<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
      <li class="basic-tab-nav active"><a data-toggle="tab" href="#tabBasic" aria-expanded="true">Basic</a></li>
      {{-- <li class="content-tab-nav"><a data-toggle="tab" href="#tabContent" aria-expanded="false">Content</a></li> --}}
      <li class="image-tab-nav"><a data-toggle="tab" href="#tabImage"  aria-expanded="false" >Image</a></li>
    </ul>
    <div class="tab-content">
        <div id="tabBasic" class="tab-pane active">
            <div class="form-group title-group">
                <label for="image_title">Title</label>
                <input type="text" id="image_title" class="form-control">

                <span class="help-block title-feedback-block" style="display: none;">
                    <strong class="title-feedback"></strong>
                </span>
            </div>

            <div class="form-group caption-group">
                <label for="image_caption">Caption</label>
                <textarea class="form-control" id="image_caption"></textarea>

                <span class="help-block">
                    <small class="caption-feedback"></small>
                </span>
            </div>

            <div class="form-group alt-group">
                <label for="image_alt">Alt text</label>
                <input type="text" class="form-control" id="image_alt">

                <span class="help-block">
                    <small class="alt-feedback"></small>
                </span>
            </div>

            <div class="form-group" @if(!$show_tags) style="display: none;" @endif>
                <label for="image_tags">Tags</label>
                <select id="image_tags" multiple="multiple" class="form-control js-states " name="tags[]">
                    @foreach ($tags as $tag)
                        <option value="{{ $tag->id }}">{{ $tag->title }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- <div id="tabContent" class="tab-pane">
            <textarea id="content_editor" name="editor" class="content_editor"></textarea>
        </div> --}}

        <div id="tabImage" class="tab-pane quickedit">
          <div class="form-group">
              <img class="img-responsive" id="edited_image" src="" alt="">
          </div>
        </div>

    </div>
</div>

@push('scripts')
    <script>
        var simplemde = null
        var clickedContent = null
        var content_body = null
        var id = null
        var relatedTarget = null

        $('#quickEditModal').on('hidden.bs.modal', function () {
            @if($show_tags)
                $("#image_tags").select2('destroy')
            @endif

            $('.image-tab-nav').removeClass('active')
            $('.basic-tab-nav').addClass('active')

            $('#tabBasic').addClass('active')
            $('#tabImage').removeClass('active')
        })

        $('#quickEditModal').on('show.bs.modal', function(e) {
            relatedTarget = e.relatedTarget
            id = relatedTarget.getAttribute('data-id')
            clickedContent = $('#item'+id)
            var image_title = relatedTarget.getAttribute('data-title')
            var image_alt = relatedTarget.getAttribute('data-alt')
            var image_caption = relatedTarget.getAttribute('data-caption')
            var image_tags = relatedTarget.getAttribute('data-tags')
            var image_url = relatedTarget.getAttribute('data-url')

            // content_body = relatedTarget.getAttribute('data-body')

            $('#quickEditModal').find('.title-group').removeClass("has-error")
            $('#quickEditModal').find('.title-feedback-block').hide()
            $('#quickEditModal').find('.title-feedback').html('')

            $('#quickEditModal').find('#image_title').val(image_title)
            $('#quickEditModal').find('#image_alt').val(image_alt)
            $('#quickEditModal').find('#image_caption').val(image_caption)
            $('#edited_image').attr('src', image_url)

            var selectedTags = []
            @if($show_tags)
                // var obj = jQuery.parseJSON(image_tags)
                var obj = jQuery.parseJSON(image_tags)
                $.each(obj, function(key,value) {
                    selectedTags.push(value.id)
                })

                $('#image_tags').select2().val(selectedTags).trigger("change")
                $('#image_tags').select2({
                    tags: true,
                    width: '100%'
                })
            @endif
        })

        $("#editBtn").on("click", function(e) {
            // console.log('post: '+$('#quickEditPostForm').find('.post_id').val());
            $('#quick-edit-post-feedback-info').hide()
            $('#updatingAnimation').show()

            function updateTags(tags) {
                if(typeof tags !== 'undefined') {
                    var tags = JSON.stringify(tags)
                    tags = jQuery.parseJSON(tags)
                    var tagsMarkup = []
                    $.each(tags, function(key,tag) {
                        tagsMarkup.push(`<a href="?search=`+tag.title+`&filter=tag"><div class="tag">`+tag.title+`</div></a>`)
                    })
                    clickedContent.find('.tags_column').html(tagsMarkup.join(''))
                }
            }

            function initTags(result) {
                if(typeof result.tags !== 'undefined') {
                    var selectTag = ''
                    $.each(result.tags, function( key, tag ) {
                        selectTag += '<option value="'+tag.id+'">'+tag.title+'</option>'
                    })
                    $("#image_tags").select2("destroy")
                    $('#image_tags').empty()
                    $('#image_tags').append(selectTag)
                    $('#image_tags').select2({tags: true, placeholder: 'Select Tags...', width: '100%'})
                }
            }

            function updateImage(image)
            {
                updateTags(image.tags)
                clickedContent.find('.image_title').text(image.title)
                clickedContent.find('.image_title').addClass('gallery-image-title')
                clickedContent.find('.image_title').removeClass('gallery-image-no-title')
                clickedContent.find('.caption_column').text(image.caption)
                clickedContent.find('.alt_column').text(image.alt)
                clickedContent.find('.image_updated_at').text(image.updated_at)
            }

            $.ajax('{{ $saveurl }}', {
                method: 'POST',
                dataType:'json',
                data: {
                    id: id,
                    title: $('#image_title').val(),
                    alt: $('#image_alt').val(),
                    caption: $('#image_caption').val(),
                    tags: $('#image_tags').val(),
                    _token: Laravel.csrfToken
                },
                success: function(result) {
                    $('#updatingAnimation').hide()

                    console.log(' yo we here')

                    if(result.image.title)
                        relatedTarget.setAttribute('data-title', result.image.title)
                    if(result.image.alt)
                        relatedTarget.setAttribute('data-alt', result.image.alt)
                    if(result.image.caption)
                        relatedTarget.setAttribute('data-caption', result.image.caption)

                    relatedTarget.setAttribute('data-tags', JSON.stringify(result.image.tags))

                    // tags need to be reinitialized since user could have added new ones
                    initTags(result)

                    // update the edited item so we do not have to reload
                    updateImage(result.image)

                    $('#quickEditModal').modal('hide')
                },
                error: function(result) {
                    var errors = result.responseJSON

                    $('#tabBasic').find('.title-group').removeClass('has-error')
                    $('#tabBasic').find('.title-feedback').html('')

                    $('#updatingAnimation').hide()
                    $('#quick-edit-post-error-feedback-info').hide()
                    $('#quick-edit-post-error-feedback-info').html('')

                    if(typeof errors.message !== 'undefined') {
                        $('#quick-edit-post-error-feedback-info').show()
                        $('#quick-edit-post-error-feedback-info').html(errors.message)
                    }

                    if(typeof errors.title !== 'undefined') {
                        $('#tabBasic').find('.title-group').addClass('has-error')
                        $('#tabBasic').find('.title-feedback').html(errors.title)
                    }
                }
            })
        })
    </script>
@endpush
