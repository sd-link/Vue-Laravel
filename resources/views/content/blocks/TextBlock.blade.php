@php
    $settings = $block->getSettings();
    if($settings->editor == 'markdown') {
        $parser = new \cebe\markdown\GithubMarkdown();
        $block->content = $parser->parse($block->content);
    } else if($settings->editor == 'basic') {
        $block->content = nl2br($block->content);
    }
@endphp

    <div class="text-block {{ $settings->textClass }}" style="text-align: {{ $settings->textAlign }}">
        {!! $block->content !!}
    </div>
