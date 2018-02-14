@php
    $content = json_decode($block->content);
    $settings = $block->getSettings();
@endphp

<div class="text-input-block">
    @if($settings->renderLabel)
        @if($settings->renderLabelStyle == 'inline')
            <span class="{{ $settings->lableClass }}">{{ $content->label }}:</span> <span class="{{ $settings->inputClass }}">{!! $content->value !!}</span>
        @else
            <div class="{{ $settings->lableClass }}">
                {{ $content->label }}
            </div>
            <div class="{{ $settings->inputClass }}">
                {{ $content->value }}
            </div>
        @endif
    @else
        <span class="{{ $settings->inputClass }}">{!! $content->value !!}</span>
    @endif
</div>
