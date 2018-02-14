@extends('layouts.app')

@section('content')
    <div class="content">
        @php
            $renderedBlocks = '';
            $deviceType = SiteSetting::getDeviceType();
            foreach ($rootBlocksIds as $blockId) {
                $block = $allBlocks[$blockId];
                $subBlocksIds = $block->subItems;
                $renderedBlock = view('content.blocks.ContainerBlock', compact('deviceType', 'block', 'subBlocksIds', 'allBlocks'))->render();
                $renderedBlocks .= $renderedBlock;
            }
        @endphp
        {!! $renderedBlocks !!}
    </div>
    <div class="sidebar" style="width: 300px;">
        this is sidebar
    </div>
@endsection

@push('scripts')

@endpush
