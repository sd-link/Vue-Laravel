
    <div class="modal" id="emailsConfigModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">Emails Config</h4>
                </div>
                  <div class="modal-body">
                      <div class="form-group">
                            <label for="">Subject</label>
                            <input id="email_subject" type="text" class="form-control">
                      </div>
                      <div class="form-group form-emails-config">
                            <label for="">Message</label>
                            <textarea id="email_message" class="form-control"></textarea>
                      </div>
                  </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Cancel</button>
                    <button id="saveEmailsConfigBtn" type="button" class="btn btn-primary pull-right">Save</button>
                </div>
            </div>
          <!-- /.modal-content -->
        </div>
      <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->


@push('scripts')
    <script src="{{asset('js/init_simplemde.js')}}"></script>
    <script>
        var simplemde = null;

        $('#emailsConfigModal').on('hidden.bs.modal', function () {
            if(simplemde != null) {
                simplemde.toTextArea();
                simplemde = null;
            }
        })
        var configEmail = 'admin'
        // Quick Edit Page Modal
        $('#emailsConfigModal').on('show.bs.modal', function(e) {
            var modal = $(this);
            clicker = e.relatedTarget;
            configEmail = clicker.getAttribute('data-email-config')

            if(simplemde != null) {
                simplemde.toTextArea();
                simplemde = null;
            }

            var options = ["heading-1", "heading-2", "heading-3", "|",  "bold", "italic", "horizontal-rule", "|", "quote", "unordered-list", "ordered-list", "|", "link", "image", "|", "preview"];
            simplemde = initSimpleMde("email_message", options)

            var doc = simplemde.codemirror.getDoc()

            if(configEmail == 'admin') {
                $('#email_subject').val(emails.admin.subject)
                doc.setValue(emails.admin.message)
            }
            else {
                $('#email_subject').val(emails.submitter.subject)
                doc.setValue(emails.submitter.message)
            }
        });

        $("#saveEmailsConfigBtn").on("click", function(e) {
            var content = null;

            if(simplemde) {
                if(configEmail == 'admin') {
                    emails.admin.message = simplemde.value()
                    emails.admin.subject = $('#email_subject').val()
                }
                else {
                    emails.submitter.message = simplemde.value()
                    emails.submitter.subject = $('#email_subject').val()
                }
            }
            $('#emailsConfigModal').modal('hide')

        });

    </script>
@endpush
