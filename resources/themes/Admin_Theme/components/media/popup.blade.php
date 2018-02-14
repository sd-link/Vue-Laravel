
    <div class="modal" id="mediaModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">Featured Image 5</h4>
                </div>

                  <div class="modal-body">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                          <li class="upload-tab-nav active"><a data-toggle="tab" href="#tabUpload" aria-expanded="true">Upload</a></li>
                          <li class="library-tab-nav"><a data-toggle="tab" href="#tabLibrary" aria-expanded="false">Library</a></li>
                        </ul>
                        <div class="tab-content">

                            <!-- /.Upload Tab -->
                          <div id="tabUpload" class="tab-pane active">
                            <form action="{{ route('backend.media.upload')}}" method="post" id="mediaUploadDropzone" class="dropzone-upload">
                                <div id="dropzone-previews" class="dz-default dz-message">
                                  <div id="uploadMsg" class="message">
                                    <i class="fa fa-cloud-upload" style="font-size: 120px;"></i>
                                    <h3>Drag and drop or click here to upload your images.</h3>
                                    <div>Max 5 MB per file.</div>
                                    {{-- <div style="margin-top: 10px;">
                                        <select class="" name="">
                                            <option value="">Pick Category</option>
                                        </select>
                                    </div> --}}
                                  </div>
                                </div>
                                <div style="clear: both;"></div>
                                {{ csrf_field() }}

                            </form>
                          </div>

                          <!-- /.Media Library Tab -->
                          <div id="tabLibrary" class="tab-pane quickedit scrollbar-inner" style="height: 350px; overflow-y: auto;">
                              <input type="hidden" id="image_id" class="form-control">

                              <div id="library_images" style="padding: 30px 0px;"></div>
                          </div>
                          <!-- /.tab-pane -->
                        </div>
                        <!-- /.tab-content -->
                    </div>

                  </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Cancel</button>
                    <button id="setFeaturedImageBtn" type="button" class="btn btn-primary pull-right" disabled="true"> Set Featured Image</button>
                </div>
            </div>
          <!-- /.modal-content -->
        </div>
      <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

@push('scripts')
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.scrollbar/0.2.11/jquery.scrollbar.min.js"></script> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.1.1/dropzone.js"></script>
    <script src="{{asset('js/mydropzone.js')}}?{{ str_random(7) }}"></script>
    <script id="popup">
        // jQuery('.scrollbar-inner').scrollbar();

        var myDropzone = new Dropzone("#mediaUploadDropzone", Dropzone.options.myAwesomeDropzone);

        var selectedImage = null;
        var total = 0;
        var clickedModel = null;
        var clickedCell = null;
        var latestLibraryImages = null;



        console.log('right')

        $("#setFeaturedImageBtn").on("click", function(e){
            console.log('GREG TEST 0');
            return false;
            setFeaturedImage(clickedModel, selectedImage);
        });

        $("#removeFeaturedImageBtn").on("click", function(e){
            removeFeaturedImage(e.target.getAttribute('data-id'));
        });

        function initImages() {
            $(".library_image").unbind();
            $(".library_image").on("click", function(e) {
                $('.selected-library-image').removeClass('selected-library-image');
                $(this).addClass('selected-library-image');
                $('#setFeaturedImageBtn').prop('disabled', false);
                selectedImage = $(this).attr('data-id');
            });
        }

        initImages();

        function removeFiles() {
            $('div.dz-success').remove();
            if(typeof freshLibraryImages !== 'undefined') {
                var images = "";
                $.each(freshLibraryImages, function( key, image ) {
                    var library_image_div = '<div class="library_image" data-id="'+image.id+'" style="position: relative; width: 128px; height: 128px; float:left; margin: 2px; margin-right: 5px;">';
                    images += library_image_div + '<img class="" src="'+image.thumb+'"></div>';
                });

                $('#library_images').prepend(images);
                initImages();
            }
            $('.nav-tabs a[href="#tabLibrary"]').tab('show');
        }

        $('.upload-tab-nav').on('shown.bs.tab', 'a[data-toggle="tab"]', function (e) {
            // alert('why wont this trigger?');
        });

        $('.library-tab-nav').unbind();
        $('.library-tab-nav').on('shown.bs.tab', 'a[data-toggle="tab"]', function (e) {
            $('div.dz-error').remove();
            $( '#uploadMsg' ).show();
        });

        $('#mediaModal').on('show.bs.modal', function(e) {
            console.log('show modal 1')
            var id = e.relatedTarget.getAttribute('data-id');
            clickedCell = e.relatedTarget;
            // console.log('clickedCell: ' + JSON.stringify(clickedCell));
            clickedModel = id;
            selectedImage = $(clickedCell).find('.featured_image').attr('data-image-id');

            // go back to top
            // $('#tabLibrary').scrollTop(); <-- does not work, does not scroll back to the top of library for some reason, try to fix later

            getLatestLibraryImages(selectedImage);

            $('#setFeaturedImageBtn').prop('disabled', true);
        })

        // $('#mediaModal').on('show.bs.modal', function(e) {
        //     console.log('show modal 2')
        //
        // })

        function getLatestLibraryImages(image_id) {
            // console.log('getLatestLibraryImages: '+image_id);
            $('.nav-tabs a[href="#tabUpload"]').tab('show');
            total = 0;
            $.ajax("{{route('backend.media.latest')}}", {
                method: 'GET',
                data: {
                    image_id: image_id
                },
                dataType:'json',
                success: function(result) {
                    var images = ""
                    $('#library_images').empty()

                    if(typeof result.image !== 'undefined') {
                        $.each( result.image, function( key, image ) {
                            var library_image_div = '<div class="library_image selected-library-image" data-id="'+image.id+'" style="position: relative; width: 128px; height: 128px; float:left; margin: 2px; margin-right: 5px;">';
                            images += library_image_div + '<img class="" src="'+image.thumb+'"></div>'

                            selectedImage = image.id
                            $('#setFeaturedImageBtn').prop('disabled', false)
                            // console.log('echo: ' + image.id)
                            total += 1
                        });
                    }

                    if(typeof result.images !== 'undefined') {
                        latestLibraryImages = result.images.data

                        $.each( latestLibraryImages, function( key, image ) {
                            var library_image_div = '<div class="library_image" data-id="'+image.id+'" style="position: relative; width: 128px; height: 128px; float:left; margin: 2px; margin-right: 5px;">';
                            images += library_image_div + '<img class="" src="'+image.thumb+'"></div>'
                        });
                        total += result.total
                        if(total > 0)
                            $('.nav-tabs a[href="#tabLibrary"]').tab('show')
                    }

                    $('#library_images').append(images+"<div style='clear: both;'></div>")
                    initImages()

                },
                error: function(result) {
                    alert('error getting images')
                }
            });

            return total
        }

        function updateFeaturedImageDiv(url)
        {
            @if($mode == 'single')
                if(url) {
                    $('.set_featured_image_text').hide();
                    $('.set_featured_image').addClass('featured-image-set');
                    $('.set_featured_image').removeClass('featured-image-not-set');
                    $('.featured_image').attr('src', url);
                    $('.featured_image').attr('data-image-id', selectedImage);
                    $('.featured_image').show();
                    $('#removeFeaturedImageBtn').show();
                    $('#mediaModal').modal('hide');
                } else {
                    $('.set_featured_image_text').show();
                    $('.featured_image').hide();
                    $('.featured_image').attr('src', '');
                    $('.featured_image').attr('data-image-id', '');
                    $('.set_featured_image').addClass('featured-image-not-set');
                    $('.set_featured_image').removeClass('featured-image-set');
                    $('#removeFeaturedImageBtn').hide();
                    $('#mediaModal').modal('hide');
                }
            @elseif($mode == 'index')
                if(url) {
                    $(clickedCell).find('.featured_image').attr('src', url);
                    $(clickedCell).find('.featured_image').show();
                    $(clickedCell).find('.featured_image').attr('data-image-id', selectedImage);
                    $('#mediaModal').modal('hide');
                } else {
                    $(clickedCell).find('.featured_image').hide();
                    $(clickedCell).find('.featured_image').attr('src', '');
                    $(clickedCell).find('.featured_image').attr('data-image-id', '');
                    $('#mediaModal').modal('hide');
                }
            @endif
        }

        function setFeaturedImage(model_id, image_id) {
            console.log('GREG TEST');
            $.ajax('{{route("backend.media.set.featuredimage")}}', {
                method: 'POST',
                dataType:'json',
                data: {
                  id: model_id,
                  image_id: image_id,
                  _token: Laravel.csrfToken
                },
                success: function(result) {
                    updateFeaturedImageDiv(result.image.medium);
                },
                error: function(result) {
                    $('#mediaModal').modal('hide');
                    var errors = result.responseJSON;
                    $('#featured_image_feedback_info').show().delay(3000).fadeOut();;
                    $('#featured_image_feedback_text').html(errors.message);
                }
            });
        }

        function removeFeaturedImage(model_id) {
            $.ajax('{{route("backend.media.remove.featuredimage")}}', {
                method: 'POST',
                dataType:'json',
                data: {
                  id: model_id,
                  _token: Laravel.csrfToken
                },
                success: function(result) {
                    updateFeaturedImageDiv(null);
                },
                error: function(result) {
                    var errors = result.responseJSON;
                    $('#featured_image_feedback_info').show().delay(3000).fadeOut();;
                    $('#featured_image_feedback_text').html(errors.message);
                }
            });
        }

    </script>
@endpush
