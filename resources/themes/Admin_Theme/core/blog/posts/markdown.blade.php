@extends('layouts.admin')

@section('content')
    <form role="form" id="postForm" method="post">
        <textarea name="postBody" id="postBody" cols="30" rows="10">some text here</textarea>
    </form>
@endsection


@push('scripts')

<!-- CK Editor -->
{{-- <script src="https://cdn.ckeditor.com/4.5.7/standard/ckeditor.js"></script> --}}
{{-- <script src="http://cdn.ckeditor.com/4.6.1/standard-all/ckeditor.js"></script> --}}

{{-- <script>
    $('.tags-multiple').select2().val({!! json_encode($post->tag_ids) !!}).trigger('change');
    $('.tags-multiple').select2({tags: true});

    var config = {
        extraPlugins: 'image2',
        height: 280,
        allowedContent: true
    };

    var post = CKEDITOR.replace( 'postBody', config );

</script> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/markdown-it/8.3.1/markdown-it.js"></script>
    <script src="{{asset('js/markdown-it-sub.min.js')}}"></script>
    <script src="{{asset('js/markdown-it-sup.min.js')}}"></script>
    <script src="{{asset('js/markdown-it-footnote.min.js')}}"></script>
    <script src="{{asset('js/markdown-it-deflist.min.js')}}"></script>

<script>
    $(document).ready(function() {

        var md = window.markdownit({
            html:         true,        // Enable HTML tags in source
            breaks:       false,        // Convert '\n' in paragraphs into <br>
            inkify:      true,        // Autoconvert URL-like text to links

                  // Enable some language-neutral replacement + quotes beautification
            typographer:  true,

        }).use(window.markdownitSub).use(window.markdownitSup).use(window.markdownitFootnote).use(window.markdownitDeflist);

        var simplemde = new SimpleMDE({
            previewRender: function(plainText) {
                return md.render(`# test`); // Returns HTML from a custom parser
            },
        });

    });
</script>

@endpush
