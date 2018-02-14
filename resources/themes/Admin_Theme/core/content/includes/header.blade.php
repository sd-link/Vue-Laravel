
    <div class="row-header">
        @if($settings->indexPageShowStatus)
            <div class="column status_column">Status</div>
        @endif
        @if($settings->indexPageShowAuthor)
            <div class="column author_column">Author</div>
        @endif
        <div class="column title_column">Title</div>
        @if($settings->indexPageShowFeaturedImage)
            <div class="column featured_image_column">Featured Image</div>
        @endif
        @if($settings->indexPageShowCreatedUpdated)
            <div class="column created_column">Created</div>
            <div class="column updated_column">Updated</div>
        @endif
        <div class="column actions_column">Actions</div>
    </div>
