@php
    $settings = $block->getSettings();
@endphp
<div class="slide-image" style="display: flex; flex-direction: row; alignItems: center; width: 50%; justifyContent: {{ $settings->imagePosition }}">
    <img src="{!! $block->content !!}" class="{{ $settings->imageResponsive }} {{ $settings->contentClass }}">
</div>
