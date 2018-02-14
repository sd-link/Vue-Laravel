@php
    $settings = $block->getSettings();
@endphp

<div class="{{ $settings->headerClass }}">{{ $block->content }}</div>
