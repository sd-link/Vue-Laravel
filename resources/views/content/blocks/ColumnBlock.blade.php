@php
    $settings = $column->getSettings();
@endphp
<div class="column-block column-block-custom-{{ $column->id }} {{ $settings->columnClass }}">
    @php
        $renderedBlocks = '';
        foreach ($subBlocksIds as $blockId) {
            $block = $allBlocks[$blockId];
            $subBlocksIds = $block->subItems;
            $blockType = str_replace('Template', '', $allBlocks[$blockId]->type);
            $renderedBlock = view('content.blocks.'.$blockType, compact('block', 'subBlocksIds', 'allBlocks'))->render();
            $renderedBlocks .= $renderedBlock;
        }
    @endphp
    {!! $renderedBlocks !!}
</div>

@push('contentblockcss')
<style>
    .column-block-custom-{{ $column->id }} {
        /*min-height: 50px;*/
        height: {{ $settings->height }};
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

        @if($settings->display == 'flex')
            display: -webkit-box;
            display: -moz-box;
            display: -ms-flexbox;
            display: -webkit-flex;
            display: flex;
            flex-direction: {{$settings->flexDirection}};
            justify-content: {{$settings->justifyContent}};
            align-items: {{$settings->alignItems}};
        @else
            display: block;
        @endif

        width: {{ $settings->width }};
        /*border: 1px solid lightgrey;*/

        margin: 0 {{ $columnSpacing }};

        @if($settings->backgroundImage)
            background-image: url('{{$settings->backgroundImage}}');
            background-attachment: {{$settings->backgroundStyle}};
            background-position: {{$settings->backgroundPosition}};
            background-size: {{$settings->backgroundSize}};
            background-repeat: {{$settings->backgroundRepeat}};
            box-shadow: inset 0 0 0 2000px {{$settings->backgroundColor}};
        @else
            box-shadow: inset 0 0 0 2000px {{$settings->backgroundColor}};
        @endif
    }
</style>
@endpush
