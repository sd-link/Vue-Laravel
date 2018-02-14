
    <div class="modal" id="quickEditImageModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">Image Edit</h4>
                </div>
                <form method="POST" id="quickEditImageForm">
                  {{ csrf_field() }}
                  <div class="modal-body">

                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                          <li class="basic-tab-nav active"><a data-toggle="tab" href="#tabBasic" aria-expanded="true">Basic</a></li>
                          <li class="image-description-tab-nav"><a data-toggle="tab" href="#tabImageDescription" aria-expanded="false" >Description</a></li>
                          <li class="image-tab-nav"><a data-toggle="tab" href="#tabImage"  aria-expanded="false" >Image</a></li>
                        </ul>
                        <div class="tab-content">
                            <!-- /.Page BASICS -->
                          <div id="tabBasic" class="tab-pane active">
                            <input type="hidden" class="form-control" id="image_id">

                            <div class="form-group title-group">
                                <label for="title">Title</label>
                                <input type="text" class="form-control" id="image_title">

                                <span class="help-block">
                                    <small class="title-feedback"></small>
                                </span>
                            </div>

                            <div class="form-group slug-group">
                                <label for="slug">Slug</label>
                                <input type="text" class="form-control" id="image_slug">

                                <span class="help-block">
                                    <small class="slug-feedback"></small>
                                </span>
                            </div>

                            <div class="form-group slug-group">
                                <label for="slug">Caption</label>
                                <textarea class="form-control" id="image_caption"> </textarea>

                                <span class="help-block">
                                    <small class="caption-feedback"></small>
                                </span>
                            </div>

                            <div class="form-group slug-group">
                                <label for="slug">Alt text</label>
                                <input type="text" class="form-control" id="image_alt">

                                <span class="help-block">
                                    <small class="alt-feedback"></small>
                                </span>
                            </div>

                            <div id="quick-image-edit-error-feedback-info" class="callout callout-danger" style="display: none;">
                            </div>

                            <div class="form-group" id="updatingAnimation" style="display: none;padding: 25px 5px; margin-bottom: 0px;">
                                <div class="cssload-loader updating-animation">Updating</div>
                            </div>
                          </div>
                          <!-- /.tab-pane -->
                          <div id="tabImageDescription" class="tab-pane quickedit">
                            <div id="imageEditor" class="form-group slug-group">
                                <label for="slug">Description</label>
                                <textarea class="form-control" id="image_content_editor"> </textarea>

                                <span class="help-block">
                                    <small class="slug-feedback"></small>
                                </span>
                            </div>
                          </div>
                          <!-- /.tab-pane -->
                          <div id="tabImage" class="tab-pane quickedit">
                            <div class="form-group slug-group">
                                <img class="img-responsive" id="edited_image" src="" alt="">
                            </div>
                          </div>
                          <!-- /.tab-pane -->
                        </div>
                        <!-- /.tab-content -->
                    </div>

                  </div>
                </form>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Cancel</button>
                    <button id="quickEditImageBtn" type="button" class="btn btn-primary pull-right">Update</button>
                </div>
            </div>
          <!-- /.modal-content -->
        </div>
      <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

@push('scripts')

    <script>
        $('#quickEditImageModal').on('hidden.bs.modal', function () {
            $('.content-tab-nav').removeClass('active');
            $('.basic-tab-nav').addClass('active');

            $('#tabBasic').addClass('active');
            $('#tabContent').removeClass('active');
        })
        var simplemdeImage = null;
        var content = null;
        var image_id = null;
        var clickedImage = null;

        $('#quickEditImageModal').on('hidden.bs.modal', function () {
            if(simplemdeImage != null)
                simplemdeImage.toTextArea();
            simplemdeImage = null;
            content = null;

            $('#image_content_editor').val('');

            $('.image-tab-nav').removeClass('active');
            $('.image-description-tab-nav').removeClass('active');
            $('.basic-tab-nav').addClass('active');

            $('#tabBasic').addClass('active');
            $('#tabImage').removeClass('active');
            $('#tabImageDescription').removeClass('active');
        })


        $('.image-description-tab-nav').unbind();
        $('.image-description-tab-nav').on('shown.bs.tab', 'a[data-toggle="tab"]', function (e) {
            if(simplemdeImage == null) {
                simplemdeImage = initSimpleMde("image_content_editor")

                var doc = simplemdeImage.codemirror.getDoc();
                if(content != null)
                    doc.setValue(content);
            }
        })

        // Quick Edit Image Modal
        $('#quickEditImageModal').on('show.bs.modal', function(e) {
            var $modal = $(this);

            clickedImage = e.relatedTarget;
            image_id = e.relatedTarget.getAttribute('data-id');
            content = null;

            $.ajax('{{route('backend.media.images.get')}}', {
                method: 'POST',
                dataType:'json',
                data: {
                    image_id: image_id,
                    _token: Laravel.csrfToken
                },
                success: function(result) {
                    image_title = result.image.title;
                    image_slug = result.image.slug;
                    image_caption = result.image.caption;
                    image_alt = result.image.alt;
                    image_medium_url = result.image.medium;
                    content = result.image.description;

                    $('#image_content_editor').val(content);
                    $('#image_title').val(image_title);
                    $('#image_slug').val(image_slug);
                    $('#image_caption').val(image_caption);
                    $('#image_alt').val(image_alt);
                    $('#edited_image').attr('src', image_medium_url);
                },
                error: function(result) {
                    var errors = result.responseJSON;
                    alert('nope')
                }
            });
        });


        $("#quickEditImageBtn").on("click", function(e){
            // console.log('Page: '+$('#quickEditImageForm').find('.page_id').val());

            $('#quick-edit-page-feedback-info').hide();
            $('#updatingAnimation').show();

            var content = null;

            if(simplemdeImage) {
                content = simplemdeImage.value();
            }

            if(content == null) {
                content = $('#image_content_editor').val();
            }

            $.ajax('{{route('backend.media.images.update')}}', {
                method: 'POST',
                dataType:'json',
                data: {
                    image_id: image_id,
                    title: $('#image_title').val(),
                    slug: $('#image_slug').val(),
                    caption: $('#image_caption').val(),
                    alt: $('#image_alt').val(),
                    content: content,
                    _token: Laravel.csrfToken
                },

                success: function(result) {
                    $('#quickEditImageModal').modal('hide');
                    $('#updatingAnimation').hide();
                    $(clickedImage).html(result.image.title);
                },
                error: function(result) {
                    var errors = result.responseJSON;

                    $('#updatingAnimation').hide();
                    $('#quick-edit-post-error-feedback-info').hide();
                    $('#quick-edit-post-error-feedback-info').html('');

                    if(typeof errors.message !== 'undefined'){
                        $('#quick-edit-post-error-feedback-info').show();
                        $('#quick-edit-post-error-feedback-info').html(errors.message);
                    }
                }
            });
        });

  </script>
@endpush
