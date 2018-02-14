<div class="pull-left">
    <small><a href="{{route('backend.comments.index')}}">All</a>({{$commentsCount}})</small>
    ·
    <small><a href="?status=approved">Approved</a>({{$approvedCount}})</small>
    ·
    <small><a href="?status=pending">Pending</a>({{$pendingCount}})</small>
    ·
    <small><a href="?status=spam">Spam</a>({{$spamCount}})</small>
</div>

<div class="pull-right"><small>Found ({{$returnedCommentsCount}})</small></div>
<div style="clear: both;"></div>
