
    <div class="search_options pull-left">
        <div style="display: inline-flex;">
            <div style="float: left; margin-right: 1px;"><input id="search" class="form-control" type="text" placeholder="Search" style="padding-left: 5px; width: 100%;"></div>
            <div style="float: left; margin-right: 1px;"><select id="filter" style="width: 100px;" class="form-control">
                @foreach ($select_options as $key => $option)
                    <option value="{{ $option['value'] }}">{{ $option['text'] }}</option>
                @endforeach
                {{-- <option value="title">Title</option>
                <option value="username">Username</option>
                <option value="category">Category</option>
                <option value="tag">Tag</option> --}}
            </select></div>
            <div id="searchBtn" style="float: left;"><button class="btn btn-primary">Search</button></div>
        </div>
    </div>
