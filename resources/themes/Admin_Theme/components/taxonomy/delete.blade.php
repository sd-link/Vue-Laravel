
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

            $('#deleteModal').on('show.bs.modal', function(e) {
                var $modal = $(this)
                id = e.relatedTarget.getAttribute('data-id')
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
                        $("#item"+id).remove()
                        $("#deleteModal").modal('hide')
                    },
                    error: function(result) {
                        var errors = result.responseJSON
                        console.log(errors)
                    }
                })
            })
        </script>
    @endpush
