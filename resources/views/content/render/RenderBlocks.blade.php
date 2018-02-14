@php
    $renderedBlocks = '';
    foreach ($subBlocksIds as $blockId) {
        $block = $allBlocks[$blockId];
        $subBlocksIds = $block->subItems;
        $blockType = str_replace('Template', '', $allBlocks[$blockId]->type);
        $renderedBlock = view('content.blocks.'.$blockType, compact('deviceType', 'block', 'subBlocksIds', 'allBlocks'))->render();
        $renderedBlocks .= $renderedBlock;
    }
@endphp
{!! $renderedBlocks !!}
