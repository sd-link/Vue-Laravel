    {{-- <form action="{{ route('backend.media.galleries.upload')}}" method="post" id="galleryUploadDropzone" class="dropzone-upload dropzone-gallery">
        <input type="hidden" name="id" value="{{ $gallery->id }}">
        <div id="dropzone-previews" class="dz-default dz-message dropzone-gallery-message">
            <div id="uploadMsg" class="message">
                <h2>Drag and Drop or Click Here</h2>
                <div>to upload your images. Max 5 MB per file.</div>
            </div>
        </div>
        <div style="clear: both;"></div>
        {{ csrf_field() }}
    </form> --}}

    <form action="{{ route('backend.media.galleries.upload')}}" method="post" id="galleryUploadDropzone" class="dropzone-upload dropzone-gallery">
        <input type="hidden" name="id" value="{{ $gallery->id }}">
        <div id="galleryUploadPreviews" class="dz-default dz-message dropzone-gallery-message">
            <div id="galleryUploadMessage" class="message">
                <h2>Drag and Drop or Click Here</h2>
                <div>to upload your images. Max 5 MB per file.</div>
            </div>
        </div>
        <div style="clear: both;"></div>
        {{ csrf_field() }}
    </form>

    <div class="form-group" style="margin-top: 25px;"><small>Drag images around to order them, order is automatically saved.</small></div>

    <div id="gallery-images" style="padding: 5px 0px;">
        @foreach ($gallery->images as $image)
            <div id="item{{ $image->id }}" class="gallery-image" data-id="{{ $image->id }}">
                @if($image->title)
                    <div class="text-center gallery-image-title image_title">{{ $image->title }}</div>
                @else
                    <div class="text-center gallery-image-no-title image_title">{{ $image->title }}</div>
                @endif
                <div>
                    <img class="img-responsive" src="{{ $image->thumb }}">
                </div>

                <div class="text-center gallery-image-footer">
                    <div class="pull-right">
                        <button id="editBtn" class="btn btn-xs btn-primary action-btn"
                            data-toggle="modal"
                            data-target="#quickEditModal"
                            data-id="{{ $image->id }}"
                            data-title="{{ $image->title }}"
                            data-alt="{{ $image->alt }}"
                            data-caption="{{ $image->caption }}"
                            data-tags="{{ $image->tags }}"
                            data-url="{{ $image->medium }}"
                            type="button"><i class="fa fa-edit"></i>
                        </button>

                        <button class="btn btn-xs btn-danger action-btn"
                            data-toggle="modal"
                            data-target="#deleteModal"
                            type="button"><i class="fa fa-trash"></i>
                        </button>
                    </div>
                    <div style="clear: both;">
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div id="clear" style="clear: both;"></div>

    @push('scripts')
        <script id="upload">
            function removeFiles() {
                $('div.dz-success').remove()
                $('div.dz-error').remove()
                if(typeof freshLibraryImages !== 'undefined') {
                    var images = ""
                    $.each(freshLibraryImages, function( key, image ) {
                        var gallery_image_div = '<div id="item'+image.id+'" class="gallery-image ui-sortable-handle" data-id="'+image.id+'">'
                        images += gallery_image_div + ` <div class="text-center gallery-image-no-title image_title"></div>
                                                        <div>
                                                            <img class="img-responsive" src="`+image.thumb+`">
                                                        </div>
                                                        <div class="text-center gallery-image-footer">
                                                            <div class="pull-right">
                                                                <button id="editBtn" class="btn btn-xs btn-primary action-btn"
                                                                    data-toggle="modal"
                                                                    data-target="#quickEditModal"
                                                                    data-id="`+image.id+`"
                                                                    type="button"><i class="fa fa-edit"></i>
                                                                </button>
                                                                <button class="btn btn-xs btn-danger action-btn"
                                                                    data-toggle="modal"
                                                                    data-target="#deleteModal"
                                                                    type="button"><i class="fa fa-trash"></i>
                                                                </button>
                                                            </div>
                                                            <div style="clear: both;">
                                                            </div>
                                                        </div>
                                                </div>`

                    });
                    $('#gallery-images').append(images)
                    // $('#gallery-images').append('<div id="clear" style="clear: both;"></div>')

                    initImages()
                }

                $('#uploadMsg').show()
                $('#galleryUploadMessage').show()
            }
        </script>
    @endpush
