
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Save Options</h3>
            <i class="fa fa-info-circle pull-right" style="cursor: pointer;" aria-hidden="true"></i>
        </div>

        <div class="box-body">
            <div class="form-group sidebar">
                <table class="table noselect">
                    @if($mode == 'edit')
                        <tr @if(!$show_author) style="display: none;" @endif>
                            <td style="width: 75px;">Author:</td>
                            <td>
                                <span id="author"><b>{{ $content->author->firstname }} {{ $content->author->lastname }}</b></span>
                            </td>
                        </tr>
                    @endif
                    <tr>
                        <td style="width: 75px;">Status:</td>
                        <td>
                            @if($mode == 'edit')
                                @if($content->status == Content::PUBLISH)
                                    <span id="status" data-status="1" class="label label-info" style="cursor: pointer; font-size: 14px;">
                                        Published
                                    </span>
                                @elseif($content->status == Content::DRAFT)
                                    <span id="status" data-status="2" class="label label-info" style="cursor: pointer; font-size: 14px;">
                                        Draft
                                    </span>
                                @elseif($content->status == Content::SCHEDULE)
                                    <span id="status" data-status="2" class="label label-info" style="cursor: pointer; font-size: 14px;">
                                        Scheduled
                                    </span>
                                @endif
                            @else
                                <span id="status" data-status="1" class="label label-info" style="cursor: pointer; font-size: 14px;">Publish</span>
                            @endif
                        </td>
                    </tr>

                    @if($mode == 'edit')
                        <tr>
                            <td>Visibility:</td>
                            <td>
                                @if($content->status == Content::PUBLISH)
                                    <b><span id="visibility" data-visibility="1">Visible</span></b>
                                @elseif($content->status == Content::DRAFT)
                                    <b><span id="visibility" data-visibility="2">Not Visible</span></b>
                                @elseif($content->status == Content::SCHEDULE)
                                    <b><span id="visibility" data-visibility="2">Not Visible</span></b>
                                @endif
                            </td>
                        </tr>
                    @endif

                    @if($mode == 'create')
                        <tr>
                            <td>Date:</td>
                            <td>
                                <span id="publish_right_now" style="font-weight: bold; cursor: pointer;">Immediately</span>
                                <input id="publish_date" type="text" value="" style="background-color: transparent; display: none; width: 130px; font-weight: bold; border: 0px solid red; cursor: pointer;">
                                <small id="reset_publish_date" style="display: none; cursor: pointer; color: #3c8dbc;">reset</small>
                            </td>
                        </tr>
                    @endif


                     @if($show_access)
                        <tr>
                            <td>Access:</td>
                            <td>
                                @if($mode == 'edit')
                                    @if($content->access == Content::EVERYONE)
                                        <span id="access" data-access="1" class="label label-info" style="cursor: pointer; font-size: 14px;">Everyone</span>
                                    @elseif($content->access == Content::MEMBERS)
                                        <span id="access" data-access="2" class="label label-info" style="cursor: pointer; font-size: 14px;">Members</span>
                                    @elseif($content->access == Content::SUBSCRIBERS)
                                        <span id="access" data-access="3" class="label label-info" style="cursor: pointer; font-size: 14px;">Paying Members</span>
                                    @else
                                        <span id="access" data-access="1" class="label label-info" style="cursor: pointer; font-size: 14px;">Everyone</span>
                                    @endif
                                @else
                                    <span id="access" data-access="1" class="label label-info" style="cursor: pointer; font-size: 14px;">Everyone</span>
                                @endif
                            </td>
                        </tr>
                    @endif

                    @if($show_password)
                        <tr>
                            <td style="vertical-align: middle;">Password:</td>
                            <td style="width: 178px;">
                                @if($mode == 'edit')
                                    <input id="password" type="text" value="{{ $content->password }}" placeholder="enter password" class="form-control" style="height: 28px;">
                                @else
                                    <input id="password" type="text" placeholder="enter password" class="form-control" style="height: 28px;">
                                @endif
                            </td>
                        </tr>
                    @endif
                </table>
            </div>
        </div>

        <div class="box-footer">
            <button id="saveBtn" style="width: 70px;" class="btn btn-primary pull-right" type="submit">Save</button>
        </div>
    </div>
