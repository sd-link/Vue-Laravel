@php
    $settings = $block->getSettings();
    $currentBlock = $block;
@endphp

<div class="slide-layer slide-layer-custom-{{ $currentBlock->id }}">
    @php
        $renderedBlocks = '';
        foreach ($subBlocksIds as $blockId) {
            $block = $allBlocks[$blockId];
            $subBlocksIds = $block->subItems;
            $blockType = str_replace('Template', '', $allBlocks[$blockId]->type);
            $renderedBlock = view('content.blocks.slider.'.$blockType, compact('block', 'subBlocksIds', 'allBlocks'))->render();
            $renderedBlocks .= $renderedBlock;
        }
    @endphp
    {!! $renderedBlocks !!}
</div>

@push('contentblockcss')
<style>
    .slide-layer-custom-{{ $currentBlock->id }} {
        display: {{ $settings->display }};
        flex-direction: {{ $settings->flexDirection }};
        justify-content: {{ $settings->justifyContent }};
        align-items: {{ $settings->alignItems }};

        @if($settings->paddingResponsive)
            @if(SiteSetting::isDesktop())
                padding: {{$settings->padding}};
            @elseif(SiteSetting::isTablet())
                padding: {{$settings->paddingTablet}};
            @elseif(SiteSetting::isMobile())
                padding: {{$settings->paddingMobile}};
            @endif
        @else
            padding: {{$settings->padding}};
        @endif
    }
</style>
@endpush
