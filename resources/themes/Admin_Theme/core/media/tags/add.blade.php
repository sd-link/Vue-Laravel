
    @component('components.modal',['id' => 'addNewTagModal', 'class' => 'modal-dialog modal-medium', 'title' => 'Add Tag'])
        @slot('content')
            <div id="tabBasic">
                <div id="title-form-group" class="form-group new_tag_title">
                    <label for="title">Title</label>
                    <input type="text" class="form-control" id="new_tag_title">
                    <span id="title-feedback-block" class="help-block" style="display: none;">
                        <strong id="title-feedback"></strong>
                    </span>
                </div>

                <div class="form-group new_tag_description">
                    <label for="slug">Description</label>
                    <textarea id="new_tag_description" name="description" class="form-control" placeholder="Enter tag description"></textarea>

                    <span class="help-block">
                        <small class="alt-feedback"></small>
                    </span>
                </div>

                <div id="quick-image-edit-error-feedback-info" class="callout callout-danger" style="display: none;">
                </div>
            </div>
        @endslot

        @slot('footer')
            <button id="addNewTagBtn" type="button" class="btn btn-primary pull-right">Add New</button>
        @endslot


        @slot('script')
            <script>
                $("#addNewTagBtn").on("click", function(e) {
                    $.ajax("{{ $url }}", {
                        method: 'POST',
                        dataType:'json',
                        data: {
                            title: $("#new_tag_title").val(),
                            description: $("#new_tag_description").val(),
                            _token: Laravel.csrfToken
                        },
                        success: function(result) {
                            $("#title-form-group").removeClass("has-error");
                            $("#title-feedback-block").hide();
                            $("#title-feedback").html('');
                            var markup =
                            `<div class="row" id="item`+ result.tag.id +`">

                                {{-- TITLE --}}
                                <div class="column title_column page" style="color: rgb(60, 141, 188);">
                                    `+ result.tag.title +`
                                </div>

                                {{-- ACTIONS --}}
                                <div class="column actions_column" data-id="`+ result.tag.id +`" data-url="`+result.url+`">
                                        <button class="btn btn-xs btn-primary action-btn"
                                            data-id="`+ result.tag.id +`"
                                            data-title="`+ result.tag.title +`"
                                            data-slug="`+ result.tag.slug +`"
                                            data-description="`+ result.tag.description +`"
                                            data-toggle="modal"
                                            data-target="#editTagModal"
                                            data-backdrop="static" data-keyboard="false" type="button"><i class="fa fa-edit"></i></button>
                                        <button
                                            class="btn btn-xs btn-danger action-btn"
                                            data-toggle="modal"
                                            data-id="`+ result.tag.id +`"
                                            data-title="`+ result.tag.title +`"
                                            data-target="#deleteModal"
                                            data-backdrop="static" data-keyboard="false"
                                            type="button"><i class="fa fa-trash"></i></button>
                                </div>
                            </div>`;

                            $("#tagsList").append(markup);

                            $("#new_tag_title").val('');
                            $("#new_tag_description").val('');

                            $("#tag-feedback-text").text("Successfully added new tag.");
                            $("#tag-feedback-info").show().delay(5000).fadeOut();

                            $("#addNewTagModal").modal('hide');

                        },
                        error: function(result) {
                            var errors = result.responseJSON;
                            if(typeof errors.title !== 'undefined'){
                                $("#title-form-group").addClass("has-error");
                                $("#title-feedback-block").show();
                                $("#title-feedback").html(errors.title[0]);
                            } else if(errors.message != null) {
                                $("#title-form-group").addClass("has-error");
                                $("#title-feedback-block").show();
                                $("#title-feedback").html(errors.message);
                            }
                        }
                    });
                });
            </script>
        @endslot
    @endcomponent
