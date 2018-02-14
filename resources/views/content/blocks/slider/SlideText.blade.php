@php
    $settings = $block->getSettings();
@endphp

<div class="slide-text slide-text-custom-{{ $block->id }} {{$settings->contentClass}}" style="position: relative; width: 50%;">
    {!! $block->content !!}
</div>
