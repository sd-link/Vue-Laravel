@php
    $content = json_decode($block->content);
    $settings = $block->getSettings();
    $slides = SliderBlock::prepareSlides($allBlocks, $subBlocksIds);
    $display= ($key==0) ? '' : 'none !important';
@endphp

<li class="slide-block slide-block-custom-{{ $block->id }}" style="display: {{ $display }}">
    @if($contentRenderStyle == 'boxed')
        <div class="container" style="height: 100%;">
            <div class="layers" style="position: relative;">
                @component('content.render.rendersliderblocks', [
                    'subBlocksIds' => $subBlocksIds,
                    'allBlocks' => $allBlocks,
                ])@endcomponent
            </div>
        </div>
    @else
        <div class="layers" style="position: relative;">
            @component('content.render.rendersliderblocks', [
                'subBlocksIds' => $subBlocksIds,
                'allBlocks' => $allBlocks,
            ])@endcomponent
        </div>
    @endif
</li>

@push('contentblockcss')
<style>
    .slide-block-custom-{{$block->id}} {
        background-image: url("{{$block->content}}");
        background-size: cover;
        background-position: center center;
    }
</style>
@endpush
