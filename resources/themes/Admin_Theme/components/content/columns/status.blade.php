
<div class="column status_column" @if(!$show) style="display: none;" @endif>
    @if($single->status == Content::PUBLISH)
        <span class="label-published" style="cursor: default;">Published</span>
    @elseif($single->status == Content::DRAFT)
        <span class="label-draft">Draft</span>
    @elseif($single->status == Content::SCHEDULE)
        <span class="label-schedule">Scheduled</span>
    @endif
</div>
