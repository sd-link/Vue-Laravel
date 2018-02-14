@component('components.content.counts', [
    'contentCount' => $allCount,
    'publishedCount' => $publishedCount,
    'scheduledCount' => $scheduledCount,
    'draftCount' => $draftCount,
    'returnedContentCount' => $returnedCount
])
@endcomponent
