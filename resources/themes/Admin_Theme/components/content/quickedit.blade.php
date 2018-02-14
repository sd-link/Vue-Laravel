<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
      <li class="basic-tab-nav active"><a data-toggle="tab" href="#tabBasic" aria-expanded="true">Basic</a></li>
      <li class="content-tab-nav"><a data-toggle="tab" href="#tabContent" aria-expanded="false">Content</a></li>
    </ul>
    <div class="tab-content">
        <div id="tabBasic" class="tab-pane active">
            <div class="form-group title-group">
                <label for="title">Title</label>
                <input type="text" id="edit_content_title" class="form-control">
                <span class="help-block title-feedback-block" style="display: none;">
                    <strong class="title-feedback"></strong>
                </span>
            </div>

            <div class="form-group slug-group" @if(!$show_slug) style="display: none;" @endif>
                <label for="slug">Slug</label>
                <input type="text" id="edit_content_slug" class="form-control">
            </div>

            {{-- <div class="form-group status-group">
                <label for="status">Status</label>
                <select id="edit_content_status" name="status" class="form-control content_status">
                  <option value="{{Content::PUBLISH}}">Published</option>
                  <option value="{{Content::DRAFT}}">Draft</option>
                  <option value="{{Content::SCHEDULE}}">Schedule</option>
                </select>

                <span class="help-block">
                    <small class="status-feedback"></small>
                </span>
            </div> --}}

            <div class="form-group access-group" @if(!$show_access) style="display: none;" @endif>
                <label for="acess">Access</label>
                <select id="edit_content_access" name="acess" class="form-control content_access">
                  <option value="{{Content::EVERYONE}}">Everyone</option>
                  <option value="{{Content::MEMBERS}}">Members</option>
                  <option value="{{Content::PREMIUM_MEMBERS}}">Paying Members</option>
                </select>

                <span class="help-block">
                    <small class="access-feedback"></small>
                </span>
            </div>

            <div class="form-group" @if(!$show_categories) style="display: none;" @endif>
                <label for="categories">Categories</label>
                <select id="categories" multiple="multiple" class="form-control js-states " name="categories[]">
                    @if($show_categories)
                        @foreach ($categories as $category)
                            <option class="parent" value="{{ $category->id }}">{{ $category->title }}</option>
                            @foreach ($category->children as $child)
                                <option class="child" value="{{ $child->id }}">{{ $child->title }}</option>
                            @endforeach
                        @endforeach
                     @endif
                </select>
            </div>

            <div class="form-group" @if(!$show_tags) style="display: none;" @endif>
                <label for="tags">Tags</label>
                <select id="tags" multiple="multiple" class="form-control js-states " name="tags[]">
                    @foreach ($tags as $tag)
                        <option value="{{ $tag->id }}">{{ $tag->title }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div id="tabContent" class="tab-pane">
            <textarea id="content_editor" name="editor" class="content_editor"></textarea>
        </div>
    </div>
</div>

@push('scripts')

    <script src="{{asset('js/init_simplemde.js')}}"></script>
    <script>

        var simplemde = null
        var clickedContent = null
        var content_body = null
        var id = null
        var relatedTarget = null

        $('#quickEditModal').on('hidden.bs.modal', function () {
            if(simplemde != null) {
                simplemde.toTextArea()
                simplemde = null
            }

            @if($show_categories)
                $("#categories").select2('destroy')
            @endif

            @if($show_tags)
                // $("#tags").select2('destroy')
            @endif

            $('.content-tab-nav').removeClass('active')
            $('.basic-tab-nav').addClass('active')

            $('#tabBasic').addClass('active')
            $('#tabContent').removeClass('active')
        })

        $('#quickEditModal').on('show.bs.modal', function(e) {
            relatedTarget = e.relatedTarget
            id = relatedTarget.getAttribute('data-id')
            clickedContent = $('#item'+id)
            var content_title = relatedTarget.getAttribute('data-title')
            // var content_status = relatedTarget.getAttribute('data-status')

            var content_slug = relatedTarget.getAttribute('data-slug')
            var content_access = relatedTarget.getAttribute('data-access')
            var content_categories = relatedTarget.getAttribute('data-categories')
            var content_tags = relatedTarget.getAttribute('data-tags')
            content_body = relatedTarget.getAttribute('data-body')

            $('#quickEditModal').find('.title-group').removeClass("has-error")
            $('#quickEditModal').find('.title-feedback-block').hide()
            $('#quickEditModal').find('.title-feedback').html('')

            $('#quickEditModal').find('#edit_content_title').val(content_title)
            $('#quickEditModal').find('#edit_content_slug').val(content_slug)
            // $('#quickEditModal').find('#edit_content_status').val(content_status)
            $('#quickEditModal').find('#edit_content_access').val(content_access)

            var selectedCats = []
            @if($show_categories)
                var obj = jQuery.parseJSON(content_categories)
                $.each(obj, function(key,value) {
                    selectedCats.push(value.id)
                })

                $('#categories').select2().val(selectedCats).trigger("change");
                $('#categories').select2({
                    tags: true,
                    width: '100%',
                    templateResult: function (data) {
                        if (!data.element) { return data.text; }
                        var $element = $(data.element)
                        var $wrapper = $('<div></div>')
                        $wrapper.addClass($element[0].className)
                        $wrapper.text(data.text)
                        return $wrapper
                    }
                })
            @endif

            var selectedTags = []
            @if($show_tags)
                // var obj = jQuery.parseJSON(content_tags)
                var obj = jQuery.parseJSON(content_tags)
                $.each(obj, function(key,value) {
                    selectedTags.push(value.id)
                })

                $('#tags').select2().val(selectedTags).trigger("change")
                $('#tags').select2({
                    tags: true,
                    width: '100%'
                })
            @endif
        })

        $('.content-tab-nav').unbind()
        $('.content-tab-nav').on('shown.bs.tab', 'a[data-toggle="tab"]', function (e) {

            if(simplemde != null) {
                simplemde.toTextArea()
                simplemde = null
            }

            simplemde = initSimpleMde("content_editor")

            simplemde.codemirror.on('refresh', function() {
                if (simplemde.isFullscreenActive()) {
                    $('body').addClass('simplemde-fullscreen')
                } else {
                    $('body').removeClass('simplemde-fullscreen')
                }
            });

            var doc = simplemde.codemirror.getDoc()
            doc.setValue(content_body)
        })


        $("#editBtn").on("click", function(e) {
            // console.log('post: '+$('#quickEditPostForm').find('.post_id').val());
            $('#quick-edit-post-feedback-info').hide()
            $('#updatingAnimation').show()
            // console.log('slug: ' + $('#quickEditPostForm').find('.post_slug').val());
            var content = null

            if(simplemde) {
                content = simplemde.value()
            }

            if(content == null)
            {
                content = clickedContent.find('.content_body').text()
            }

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

            function updateCategories(categories) {
                if(typeof categories !== 'undefined') {
                    var categories = JSON.stringify(categories)
                    categories = jQuery.parseJSON(categories)
                    var categoriesMarkup = []
                    $.each(categories, function(key,category) {
                        categoriesMarkup.push(`<a href="?search=`+category.title+`&filter=category"><div class="category">`+category.title+`</div></a>`)
                    })
                    clickedContent.find('.categories_column').html(categoriesMarkup.join(''))
                }
            }

            function initCategories(result) {
                if(typeof result.categories !== 'undefined') {
                    var selectCat = ''
                    $.each(result.categories, function( key, category ) {
                        selectCat += '<option value="'+category.id+'">'+category.title+'</option>'
                    })
                    $("#categories").select2("destroy")
                    $('#categories').empty()
                    $('#categories').append(selectCat)

                    $('#categories').select2({
                        tags: true,
                        placeholder: 'Select Categories...',
                        width: '100%',
                        templateResult: function (data) {
                            if (!data.element) { return data.text; }
                            var $element = $(data.element)
                            var $wrapper = $('<div></div>')
                            $wrapper.addClass($element[0].className)
                            $wrapper.text(data.text)
                            return $wrapper
                        }
                    })
                }
            }

            function initTags(result) {
                if(typeof result.tags !== 'undefined') {
                    var selectTag = ''
                    $.each(result.tags, function( key, tag ) {
                        selectTag += '<option value="'+tag.id+'">'+tag.title+'</option>'
                    })
                    $("#tags").select2("destroy")
                    $('#tags').empty()
                    $('#tags').append(selectTag)
                    $('#tags').select2({tags: true, placeholder: 'Select Tags...', width: '100%'})
                }
            }

            function updateItem(content)
            {
                updateCategories(content.categories)
                updateTags(content.tags)
                clickedContent.find('.content_title').text(content.title)
                clickedContent.find('.content_updated_at').text(content.updated_at)
            }

            $.ajax('{{ $saveurl }}', {
                method: 'POST',
                dataType:'json',
                data: {
                    id: id,
                    title: $('#edit_content_title').val(),
                    content: content,
                    slug: $('#edit_content_slug').val(),
                    access: $('#edit_content_access').val(),
                    categories: $('#categories').val(),
                    tags: $('#tags').val(),
                    _token: Laravel.csrfToken
                },
                success: function(result) {
                    $('#updatingAnimation').hide()

                    relatedTarget.setAttribute('data-title', result.content.title)
                    relatedTarget.setAttribute('data-body', result.content.body)
                    relatedTarget.setAttribute('data-slug', result.content.slug)
                    relatedTarget.setAttribute('data-access', result.content.access)
                    relatedTarget.setAttribute('data-content', result.content.body)
                    relatedTarget.setAttribute('data-categories', JSON.stringify(result.content.categories))
                    relatedTarget.setAttribute('data-tags', JSON.stringify(result.content.tags))

                    // categories and tags need to be reinitialized since user could have added new ones
                    initCategories(result)
                    initTags(result)

                    // update the edited item so we do not have to reload
                    updateItem(result.content)

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
