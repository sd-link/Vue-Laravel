@php
    $settings = $block->getSettings();

    // dd($settings);
@endphp
<div class="columns-block columns-block-custom-{{ $block->id }} {{ $settings->columnsClass }}">
    @php
        $renderedBlocks = '';
        $columnSpacing = $settings->columnSpacing;
        foreach ($subBlocksIds as $blockId) {
            $column = $allBlocks[$blockId];
            $subBlocksIds = $column->subItems;
            $renderedBlock = view('content.blocks.ColumnBlock', compact('column', 'columnSpacing', 'subBlocksIds', 'allBlocks'))->render();
            $renderedBlocks .= $renderedBlock;
        }
    @endphp
    {!! $renderedBlocks !!}
</div>

@push('contentblockcss')
<style>
    .columns-block-custom-{{ $block->id }} {
        background-color: {{ $settings->backgroundColor }};

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

        @if($settings->marginResponsive)
            @if(SiteSetting::isDesktop())
                margin: {{$settings->margin}};
            @elseif(SiteSetting::isTablet())
                margin: {{$settings->marginTablet}};
            @elseif(SiteSetting::isMobile())
                margin: {{$settings->marginMobile}};
            @endif
        @else
            margin: {{$settings->margin}};
        @endif

        @if($settings->backgroundImage)
            background-image: url('{{$settings->backgroundImage}}');
            background-attachment: {{$settings->backgroundStyle}};
            background-position: {{$settings->backgroundPosition}};
            background-repeat: {{$settings->backgroundRepeat}};
            box-shadow: inset 0 0 0 2000px {{$settings->backgroundColor}};
        @endif
    }
</style>
@endpush
