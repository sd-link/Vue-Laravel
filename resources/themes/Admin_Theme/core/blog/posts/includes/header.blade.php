
<div class="row-header">
    <div class="column status_column" @if(!Setting::get('Admin.Content', 'blog_index_show_status')) style="display: none;" @endif>Status</div>
    <div class="column author_column" @if(!Setting::get('Admin.Content', 'blog_index_show_author')) style="display: none;" @endif>Author</div>
    <div class="column title_column">Title</div>
    <div class="column featured_image_column" @if(!Setting::get('Admin.Content', 'blog_index_show_featured')) style="display: none;" @endif>Featured Image</div>
    <div class="column categories_column" @if(!Setting::get('Admin.Content', 'blog_index_show_categories')) style="display: none;" @endif>Categories</div>
    <div class="column tags_column" @if(!Setting::get('Admin.Content', 'blog_index_show_tags')) style="display: none;" @endif>Tags</div>
    <div class="column created_column" @if(!Setting::get('Admin.Content', 'blog_index_show_created')) style="display: none;" @endif>Created</div>
    <div class="column updated_column"  @if(!Setting::get('Admin.Content', 'blog_index_show_updated')) style="display: none;" @endif>Updated</div>
    <div class="column actions_column">Actions</div>
</div>
