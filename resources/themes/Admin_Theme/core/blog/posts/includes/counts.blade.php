@component('components.content.counts', [
    'indexurl' => route('backend.blog.index'),
    'contentCount' => $content->postCount,
    'publishedCount' => $content->publishedCount,
    'scheduledCount' => $content->scheduledCount,
    'draftCount' => $content->draftCount,
    'returnedContentCount' => $content->returnedPostsCount
])
@endcomponent
