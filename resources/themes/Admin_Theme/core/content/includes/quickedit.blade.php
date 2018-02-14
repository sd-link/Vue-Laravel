
    <div class="modal" id="quickEditPageModal">
        <div class="vertical-alignment-helper">
            <div class="modal-dialog vertical-align-center">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                        <h4 class="modal-title">Quick Edit</h4>
                    </div>
                    <form method="POST" id="quickEditPageForm">
                      {{ csrf_field() }}
                      <div class="modal-body">

                        <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs">
                              <li class="basic-tab-nav active"><a data-toggle="tab" href="#tabBasic" aria-expanded="true">Basic</a></li>
                              <li class="content-tab-nav"><a data-toggle="tab" href="#tabContent"  aria-expanded="false">Content</a></li>
                            </ul>
                            <div class="tab-content">
                                <!-- /.Page BASICS -->
                              <div id="tabBasic" class="tab-pane active">
                                <input type="hidden" class="form-control page_id">

                                <div class="form-group title-group">
                                    <label for="title">Title</label>
                                    <input type="text" class="form-control page_title">

                                    <span class="help-block">
                                        <small class="title-feedback"></small>
                                    </span>
                                </div>

                                <div class="form-group slug-group">
                                    <label for="slug">Page Slug</label>
                                    <input type="text" class="form-control page_slug">

                                    <span class="help-block">
                                        <small class="slug-feedback"></small>
                                    </span>
                                </div>

                                <div class="form-group status-group">
                                    <label for="status">Status</label>
                                    <select class="form-control page_status">
                                        <option value="{{Content::PUBLISH}}">Published</option>
                                        <option value="{{Content::DRAFT}}">Draft</option>
                                        <option value="{{Content::SCHEDULE}}">Schedule</option>
                                    </select>

                                    <span class="help-block">
                                        <small class="status-feedback"></small>
                                    </span>
                                </div>

                                <div class="form-group status-group">
                                    <label for="acess">Access</label>
                                    <select class="form-control page_access">
                                      <option value="{{Content::EVERYONE}}">Everyone</option>
                                      <option value="{{Content::MEMBERS}}">Members</option>
                                      <option value="{{Content::PREMIUM_MEMBERS}}">Paying Members</option>
                                    </select>

                                    <span class="help-block">
                                        <small class="acess-feedback"></small>
                                    </span>
                                </div>

                                <div id="quick-edit-page-error-feedback-info" class="callout callout-danger" style="display: none;">
                                </div>

                                <div class="form-group" id="updatingAnimation" style="display: none;padding: 25px 5px; margin-bottom: 0px;">
                                    <div class="cssload-loader updating-animation">Updating</div>
                                </div>
                              </div>
                              <!-- /.tab-pane -->
                              <div id="tabContent" class="tab-pane quickedit">
                                    <textarea id="content_editor" name="editor" class="content_editor"></textarea>
                              </div>
                              <!-- /.tab-pane -->
                            </div>
                            <!-- /.tab-content -->
                        </div>

                      </div>
                    </form>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Cancel</button>
                        <button id="quickEditPageBtn" type="button" class="btn btn-primary pull-right">Update</button>
                    </div>
                </div>
              <!-- /.modal-content -->
            </div>
        </div>
      <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->


@push('scripts')
    <script src="{{asset('js/init_simplemde.js')}}"></script>
  <script>

        var simplemde = null;
        var clickedContent = null;

        $('#quickEditPageModal').on('hidden.bs.modal', function () {
            if(simplemde != null) {
                simplemde.toTextArea();
                simplemde = null;
            }

            $('.content-tab-nav').removeClass('active');
            $('.basic-tab-nav').addClass('active');

            $('#tabBasic').addClass('active');
            $('#tabContent').removeClass('active');
        })

        // Quick Edit Page Modal
        $('#quickEditPageModal').on('show.bs.modal', function(e) {
            var $modal = $(this);
            var id = e.relatedTarget.getAttribute('data-id');
            clickedContent = $('#row'+id);
            var status = clickedContent.find('.content_status').attr('data-status');
            var access = clickedContent.find('.content_status').attr('data-access');
            var slug = clickedContent.find('.page_slug').attr('data-slug');
            var page_title = clickedContent.find('.content_title').text();
            var page_body = clickedContent.find('.content_body').text();

            $('#quickEditPageForm').find('.page_id').val(id);
            $('#quickEditPageForm').find('.page_title').val(page_title);
            $('#quickEditPageForm').find('.content_editor').val(page_body);
            $('#quickEditPageForm').find('.page_status').val(status);
            $('#quickEditPageForm').find('.page_access').val(access);

            $('#quickEditPageForm').find('.page_slug').val(slug);

            $('.content-tab-nav').unbind();
            $('.content-tab-nav').on('shown.bs.tab', 'a[data-toggle="tab"]', function (e) {

                if(simplemde != null) {
                    simplemde.toTextArea();
                    simplemde = null;
                }

                simplemde = initSimpleMde("content_editor");

                simplemde.codemirror.on('refresh', function() {
                    if (simplemde.isFullscreenActive()) {
                        $('body').addClass('simplemde-fullscreen');
                    } else {
                        $('body').removeClass('simplemde-fullscreen');
                    }
                });

                var doc = simplemde.codemirror.getDoc();
                doc.setValue(page_body);
            })
        });

        $("#quickEditPageBtn").on("click", function(e){
            $('#quick-edit-page-feedback-info').hide();
            $('#updatingAnimation').show();

            var content = null;

            if(simplemde) {
                content = simplemde.value();
            }

            if(content == null)
            {
                content = clickedContent.find('.page_body').text();
            }

            $.ajax('{{route('backend.pages.quickupdate')}}', {
                method: 'POST',
                dataType:'json',
                data: {
                    page_id: $('#quickEditPageForm').find('.page_id').val(),
                    title: $('#quickEditPageForm').find('.page_title').val(),
                    content: content,
                    slug: $('#quickEditPageForm').find('.page_slug').val(),
                    status: $('#quickEditPageForm').find('.page_status').val(),
                    access: $('#quickEditPageForm').find('.page_access').val(),
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
        });

  </script>
@endpush
