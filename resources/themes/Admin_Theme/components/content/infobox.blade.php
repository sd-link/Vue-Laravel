    <div class="box box-primary" @if(! $show_info ) style="display: none;" @endif>
        <div class="box-header with-border">
            <h3 class="box-title"> {{ $title }}</h3>
        </div>

        <div class="box-body">
            <div class="form-group sidebar">
                <table class="table noselect">
                    <tr>
                        <td>Created:</td>
                        <td>
                            <span>
                               <b><span>{{ $content->created_at }}</span></b>
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td>Published:</td>
                        <td>
                        <span>
                            {{-- <span><b>{{$content->published_date}} {{$content->published_time}}</b></span>  --}}
                            <b><span id="published_at" style="cursor: pointer; margin-right: 8px;">{{ $content->published_at }}</span></b>
                            <input id="publish_date" type="text" value="{{ $content->published_at }}"
                            style="background-color: transparent; display: none; width: 113px; font-weight: bold; border: 0px solid red; cursor: pointer;">
                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                        </span>
                        </td>
                    </tr>
                    <tr>
                        <td>Updated:</td>
                        <td>
                        <span>
                            {{-- <span><b>{{$content->published_date}} {{$content->published_time}}</b></span>  --}}
                            <b><span id="updated_at">{{ $content->updated_at }}</span></b>
                        </span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
