@component('components.content.counts', [
    'indexurl' => route('backend.media.galleries.index'),
    'contentCount' => $galleryCount,
    'publishedCount' => $publishedCount,
    'scheduledCount' => $scheduledCount,
    'draftCount' => $draftCount,
    'returnedContentCount' => $returnedGalleryCount
])
@endcomponent
