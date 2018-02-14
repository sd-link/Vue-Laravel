
<div class="column actions_column">
    <button id="editBtn" class="btn btn-xs btn-primary action-btn"
        data-toggle="modal"
        data-target="#quickEditModal"
        data-id="{{ $single->id }}"
        data-title="{{ $single->title }}"
        data-slug="{{ $single->slug }}"
        data-body="{{ $single->body }}"
        data-categories="{{ $single->categories }}"
        data-tags="{{ $single->tags }}"
        data-status="{{ $single->status }}"
        data-access="{{ $single->access }}"
        type="button" title="Quick Edit"><i class="fa fa-edit"></i>
    </button>

    <button
        class="btn btn-xs btn-danger action-btn"
        data-id="{{ $single->id }}"
        data-title="{{ $single->title }}"
        data-toggle="modal"
        data-target="#deleteModal"
        type="button"><i class="fa fa-trash"></i>
    </button>
</div>
