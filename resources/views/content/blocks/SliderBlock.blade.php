@php
    $content = json_decode($block->content);
    $settings = $block->getSettings();
    $slides = SliderBlock::prepareSlides($allBlocks, $subBlocksIds);
@endphp
<slider-block :slides='{!! json_encode($slides) !!}' device-type="{{ $deviceType }}" v-bind="{!! $block->getSettingsFilteredForVue() !!}">
    <div class="slider-block slider-block-{{ $block->id }}" style="height: {{ $settings->height }} !important;">
        @component('content.render.renderslides', [
            'contentRenderStyle' => $settings->contentRenderStyle,
            'deviceType' => $deviceType,
            'subBlocksIds' => $subBlocksIds,
            'allBlocks' => $allBlocks,
        ])@endcomponent
    </div>
</slider-block>
