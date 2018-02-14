<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script>
    var sortableOptions = {
        update: function (event, ui) {
            var order = saveOrder();

            $.ajax({
                data: {
                    'order[]': order,
                    id: $('#id').val(),
                    _token: Laravel.csrfToken
                },
                type: 'POST',
                url: '{{ route('backend.media.galleries.order') }}'
            });
        }
    };

    $( function() {
        $('#gallery-images').sortable(sortableOptions);
        $("#gallery-images" ).disableSelection();
    });

    function saveOrder() {
        var order = new Array();

        $('#gallery-images .gallery-image').each(function() {
            order.push($(this).attr("data-id"));
        });

        return order;
    }

    function initImages() {
        $(".gallery-image").unbind();
        $(".gallery-image").on("click", function(e) {
            // $('.selected-gallery-image').removeClass('selected-gallery-image');
            // $(this).addClass('selected-gallery-image');
            selectedImage = $(this).attr('data-id');
        });

        $('#gallery-images').sortable('destroy');
        $('#gallery-images').unbind();
        $('#gallery-images').sortable(sortableOptions);
        $( "#gallery-images" ).disableSelection();
    }
</script>
