
    <div class="modal" id="deleteModal">
        <div class="vertical-alignment-helper">
            <div class="modal-dialog modal-medium vertical-align-center">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                        <h4 class="modal-title">{{ $title }}</h4>
                    </div>
                    <div class="modal-body" id="deleteModalBody">
                        {{ $content }}
                    </div>
                    <div class="modal-footer">
                        <button id="deleteBtn" type="button" class="btn btn-danger pull-right">Delete</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

@push('scripts')
    <script>

        var id = null
        var page = '{{ $page }}'

        $('#deleteModal').on('show.bs.modal', function(e) {
            console.log('is it open?')
            var $modal = $(this)
            id = e.relatedTarget.getAttribute('data-id')
            console.log('id is: ' + id)
            var title = e.relatedTarget.getAttribute('data-title')

            $('#deleteModalBody').find('#contentTitle').text(title)
        })

        $('#deleteBtn').on("click", function(event) {
            $.ajax("{{ $deleteurl }}", {
                method: 'POST',
                dataType:'json',
                data: {
                    id: id,
                    _token: Laravel.csrfToken
                },
                success: function(result) {
                    // $("#item"+id).remove()
                     getContent()
                    $("#deleteModal").modal('hide')
                },
                error: function(result) {
                    var errors = result.responseJSON
                    console.log(errors)
                }
            })
        })

        function getContent()
        {
            $.ajax('{{ $contenturl }}', {
                method: 'get',
                dataType:'json',
                data: {
                    page: page,
                    status: status,
                },
                success: function(result) {
                    $('#content').html(result.content)
                    $('#counts').html(result.counts)
                    $('#pagination').html(result.pagination)
                    //
                    // if($("#no-comments").length != 0) {
                    //     oldPage = page
                    //     page = page - 1
                    //
                    //     if(page < 1)
                    //         page = 1
                    //
                    //     console.log('page: ' + page)
                    //
                    //     if(oldPage > 1)
                    //         getComments()
                    // }

                },
                error: function(result) {
                    // enableBtn(saveBtn, 'Save');
                    var errors = result.responseJSON;
                }
            });
        }
    </script>
@endpush
