<div class="form-group">
    <h4>Images</h4>
    <table class="table">
            <tr>
                <td style="width: 160px;">Index page display as</td>
                <td>
                    <label style="margin-right: 10px;">
                        <input type="radio" name="images_index_view" value="list" checked/>
                        <img src="http://larapress.dev/images/admin/content_blog_list.png" style="width: 220px; height: auto;">
                        <div class="text-center" style="margin-top: 7px;">
                            List view
                        </div>
                    </label>

                    <label>
                        <input type="radio" name="images_index_view" value="grid" />
                        <img src="http://larapress.dev/images/admin/content_blog_grid.png" style="width: 220px; height: auto;">
                        <div class="text-center" style="margin-top: 7px;">
                            Grid view
                        </div>
                    </label>
                </td>
            </tr>
            <tr>
                <td>Grid view shows this many columns on desktop</td>
                <td>
                    <select class="form-control" id="images_display_grid_view_columns">
                        <option value="column-5">5</option>
                        <option value="column-4">4</option>
                        <option value="column-3">3</option>
                        <option value="column-2">2</option>
                        <option value="column-1">1</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Index page details</td>
                <td style="vertical-align: middle;">
                    <span style="margin-right: 10px;">
                        <input class="icheck_input" type="checkbox" id="images_index_show_author" @if(SiteSetting::get('Admin.Content', 'images_index_show_author')) checked @endif/>
                        <label for="images_index_show_author" style="margin-left: 5px;">
                            Author
                        </label>
                    </span>

                    <span style="margin-right: 10px;">
                        <input class="icheck_input" type="checkbox" id="images_index_show_title" @if(SiteSetting::get('Admin.Content', 'images_index_show_title')) checked @endif/>
                        <label for="images_index_show_title" style="margin-left: 5px;">
                            Title
                        </label>
                    </span>

                    <span style="margin-right: 10px;">
                        <input class="icheck_input" type="checkbox" id="images_index_show_caption" @if(SiteSetting::get('Admin.Content', 'images_index_show_caption')) checked @endif/>
                        <label for="images_index_show_caption" style="margin-left: 5px;">
                            Caption
                        </label>
                    </span>

                    <span style="margin-right: 10px;">
                        <input class="icheck_input" type="checkbox" id="images_index_show_created" @if(SiteSetting::get('Admin.Content', 'images_index_show_created')) checked @endif/>
                        <label for="images_show_created" style="margin-left: 5px;">
                            Created
                        </label>
                    </span>

                    <span style="margin-right: 10px;">
                        <input class="icheck_input" type="checkbox" id="images_index_show_updated" @if(SiteSetting::get('Admin.Content', 'images_index_show_updated')) checked @endif/>
                        <label for="images_show_updated" style="margin-left: 5px;">
                            Updated
                        </label>
                    </span>
                </td>
            </tr>
            <tr>
                <td>Index page title align</td>
                <td style="vertical-align: middle;">
                    <select class="form-control" id="images_index_title_align">
                        <option value="center">center</option>
                        <option value="flex-start">left</option>
                    </select>
                </td>
            </tr>

            <tr>
                <td>Index page list view image height</td>
                <td style="vertical-align: middle;">
                    <input type="text" id="images_index_image_height" class="form-control" value="{{ SiteSetting::get('Admin.Content', 'images_index_image_height') }}">
                </td>
            </tr>
    </table>
</div>
