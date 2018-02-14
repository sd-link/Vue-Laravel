

    @if($mode == 'edit')
        <textarea id="content_editor" name="editor" class="content_editor">{{ $content->body }}</textarea>
    @else
        <textarea id="content_editor" name="editor" class="content_editor"></textarea>
    @endif
