@php
    $settings = $block->getSettings();
    $content = json_decode($block->content);
    if(isset($content))
        $video = substr($content->url, strpos($content->url, "=") + 1);
@endphp

<div class="video-block">
    {{-- @if($settings->renderBlockTitle)
        <div class="{{ $settings->blockTitleClass }}">
            {{ $block->title }}
        </div>
    @endif --}}
    @if(isset($video))
        <div style="display: flex; justify-content: {{ $settings->position }};">
            <iframe width="{{ $settings->width }}" height="{{ $settings->height }}" src="https://www.youtube.com/embed/{{ $video }}" style="border: 0;"></iframe>
        </div>
    @endif
</div>
