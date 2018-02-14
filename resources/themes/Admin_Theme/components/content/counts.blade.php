
    <div style="border: 0px red solid; margin-bottom: 2px;">
        <div style="border: 0px green solid;">
            <div class="pull-left">
                <a href="{{$indexurl}}">All</a>: {{$content->allCount}}
                @if($content->publishedCount)
                <a href="?status=published">Published</a>: {{$content->publishedCount}}@endif
                @if($content->scheduledCount)
                <a href="?status=scheduled">Scheduled</a>: {{$content->scheduledCount}}@endif
                @if($content->draftCount)
                <a href="?status=drafts">Drafts</a>: {{$content->draftCount}}@endif
            </div>

            <div class="pull-right">Found: {{$content->returnedCount}}</div>
            <div style="clear: both;"></div>
        </div>
    </div>
