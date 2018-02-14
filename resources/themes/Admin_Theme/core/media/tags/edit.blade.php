
    @component('components.modal',['id' => 'editTagModal', 'class' => 'modal-dialog modal-medium', 'title' => 'Edit Tag'])
        @slot('content')
            <div id="tabBasic" class="tab-pane active">
                <input id="tag_id" type="hidden" name="" value="">
                <div class="form-group title-form-group">
                    <label for="title">Title</label>
                    <input type="text" class="form-control" id="edit_tag_title">
                    <span class="help-block title-feedback-block" style="display: none;">
                        <strong class="title-feedback"></strong>
                    </span>
                </div>

                <div class="form-group new_tag_description">
                    <label for="slug">Description</label>
                    <textarea id="edit_tag_description" name="description" class="form-control" placeholder="Enter tag description"></textarea>

                    <span class="help-block">
                        <small class="alt-feedback"></small>
                    </span>
                </div>

                <div id="quick-image-edit-error-feedback-info" class="callout callout-danger" style="display: none;">
                </div>
            </div>
        @endslot

        @slot('footer')
            <button id="editTagBtn" type="button" class="btn btn-primary pull-right">Save</button>
        @endslot

        @slot('script')
            <script>
                $("#editTagBtn").on("click", function(e) {
                    $.ajax("{{ $url }}", {
                        method: 'POST',
                        dataType:'json',
                        data: {
                            id: $("#tag_id").val(),
                            title: $("#edit_tag_title").val(),
                            description: $("#edit_tag_description").val(),
                            _token: Laravel.csrfToken
                        },
                        success: function(result) {
                            $("#title-form-group").removeClass("has-error")
                            $("#title-feedback-block").hide()
                            $("#title-feedback").html('')

                            $("#tag-feedback-text").text("Successfully updated tag.")
                            $("#tag-feedback-info").show().delay(5000).fadeOut()

                            $('#item'+result.tag.id).find('.title_column').html(result.tag.title)
                            $('#item'+result.tag.id).find('.btn-primary').attr('data-title', result.tag.title)
                            $('#item'+result.tag.id).find('.btn-primary').attr('data-description', result.tag.description)

                            $("#editTagModal").modal('hide')
                        },
                        error: function(result) {
                            var errors = result.responseJSON;
                            if(typeof errors !== 'undefined') {
                                if(typeof errors.title !== 'undefined') {
                                    $('#editTagModal').find('.title-form-group').addClass("has-error")
                                    $('#editTagModal').find('.title-feedback-block').show()
                                    $('#editTagModal').find('.title-feedback').html(errors.title[0])
                                } else if(typeof errors.message !== 'undefined') {
                                    $('#editTagModal').find('.title-form-group').addClass("has-error")
                                    $('#editTagModal').find('.title-feedback-block').show()
                                    $('#editTagModal').find('.title-feedback').html(errors.message)
                                }
                            }
                        }
                    });
                });

                $('#editTagModal').on('show.bs.modal', function(e) {
                    var id = e.relatedTarget.getAttribute('data-id')
                    var tag_title = e.relatedTarget.getAttribute('data-title')
                    var tag_description = e.relatedTarget.getAttribute('data-description')

                    $('#editTagModal').find('.title-form-group').removeClass("has-error")
                    $('#editTagModal').find('.title-feedback-block').hide()
                    $('#editTagModal').find('.title-feedback').html('')

                    $('#editTagModal').find('#tag_id').val(id)
                    $('#editTagModal').find('#edit_tag_title').val(tag_title)
                    $('#editTagModal').find('#edit_tag_description').val(tag_description)
                });
            </script>
        @endslot
    @endcomponent
