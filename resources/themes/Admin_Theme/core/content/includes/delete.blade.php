
    <div class="modal" id="deletePageModal">
        <div class="vertical-alignment-helper">
            <div class="modal-dialog vertical-align-center">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                        <h4 class="modal-title">Delete Page</h4>
                    </div>
                    <form action="{{route('backend.pages.delete')}}" method="POST" id="deletePageForm">
                      {{ csrf_field() }}
                      <div class="modal-body">
                          <input type="text" value="" name="page_id" class="form-control page_id">

                          <div class="form-group">
                                <span>
                                    Are you sure you want to delete this page?
                                </span>
                                <div style="margin-top: 10px; font-weight: bold;" id="pageToDeleteTitle"></div>
                          </div>

                          <div id="delete-error-feedback-info" class="callout callout-danger" style="display: none;">
                          </div>

                          <div class="form-group" id="deletingAnimation" style="display: none;padding: 25px 5px; margin-bottom: 0px;">
                              <div class="cssload-loader">Deleting</div>
                          </div>

                      </div>
                    </form>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Cancel</button>
                        <button id="deletePageBtn" type="button" class="btn btn-danger pull-right"><i class="fa fa-eraser" aria-hidden="true"></i> Delete</button>
                    </div>
                </div>
              <!-- /.modal-content -->
            </div>
        </div>
      <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

@push('scripts')
    <script>
        // Delete Page Modal
        $('#deletePageModal').on('show.bs.modal', function(e) {

            var $modal = $(this);
            var id = e.relatedTarget.getAttribute('data-id');
            $('#deletePageForm').find('.page_id').val(id);

            var name = $('#row'+id).find('.name').html();
            var pageTile = $('#row'+id).find('.page-title').html();

            $('#pageToDeleteTitle').html($.trim(pageTile));
        });

        $("#deletePageBtn").on("click", function(e){
            $('#delete-feedback-info').hide();
            $('#deletingAnimation').show();

            $.ajax('{{route('backend.pages.delete')}}', {
                method: 'POST',
                dataType:'json',
                data: {
                    page_id: $('#deletePageForm').find('.page_id').val(),
                    _token: Laravel.csrfToken
                },

                success: function(result) {
                    $('#deletingAnimation').hide();
                    location.reload();
                },
                error: function(result) {
                    var errors = result.responseJSON;
                    $('#deletingAnimation').hide();
                    $('#delete-error-feedback-info').show();
                    $('#delete-error-feedback-info').html(errors.message);
                }
            });
        });
    </script>
@endpush
