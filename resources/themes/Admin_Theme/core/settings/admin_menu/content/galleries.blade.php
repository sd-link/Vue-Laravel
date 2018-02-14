<div class="form-group">
    <h4>Galleries</h4>
    <table class="table">
            <tr>
                <td style="width: 160px;">Index page display as</td>
                <td>
                    <label style="margin-right: 10px;">
                        <input type="radio" name="galleries_index_view" value="list" checked/>
                        <img src="http://larapress.dev/images/admin/content_blog_list.png" style="width: 220px; height: auto;">
                        <div class="text-center" style="margin-top: 7px;">
                            List view
                        </div>
                    </label>

                    <label>
                        <input type="radio" name="galleries_index_view" value="grid" />
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
                    <select class="form-control" id="galleries_display_grid_view_columns">
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
                        <input class="icheck_input" type="checkbox" id="galleries_index_show_status" @if(Setting::get('Admin.Content', 'galleries_index_show_status')) checked @endif/>
                        <label for="galleries_index_show_status" style="margin-left: 5px;">
                            Status
                        </label>
                    </span>

                    <span style="margin-right: 10px;">
                        <input class="icheck_input" type="checkbox" id="galleries_index_show_status_date" @if(Setting::get('Admin.Content', 'galleries_index_show_status_date')) checked @endif/>
                        <label for="galleries_index_show_status_date" style="margin-left: 5px;">
                            Status Date
                        </label>
                    </span>

                    <span style="margin-right: 10px;">
                        <input class="icheck_input" type="checkbox" id="galleries_index_show_author" @if(Setting::get('Admin.Content', 'galleries_index_show_author')) checked @endif/>
                        <label for="galleries_index_show_author" style="margin-left: 5px;">
                            Author
                        </label>
                    </span>

                    <span style="margin-right: 10px;">
                        <input class="icheck_input" type="checkbox" id="galleries_index_show_featured_image" @if(Setting::get('Admin.Content', 'galleries_index_show_featured_image')) checked @endif/>
                        <label for="galleries_index_show_featured_image" style="margin-left: 5px;">
                            Featured Image
                        </label>
                    </span>

                    <span style="margin-right: 10px;">
                        <input class="icheck_input" type="checkbox" id="galleries_index_show_shortcode" @if(Setting::get('Admin.Content', 'galleries_index_show_shortcode')) checked @endif/>
                        <label for="galleries_index_show_shortcode" style="margin-left: 5px;">
                            Shortcode
                        </label>
                    </span>

                    <span style="margin-right: 10px;">
                        <input class="icheck_input" type="checkbox" id="galleries_index_show_images_count" @if(Setting::get('Admin.Content', 'galleries_index_show_images_count')) checked @endif/>
                        <label for="galleries_index_show_images_count" style="margin-left: 5px;">
                            Images Count
                        </label>
                    </span>

                    <span style="margin-right: 10px;">
                        <input class="icheck_input" type="checkbox" id="galleries_index_show_created" @if(Setting::get('Admin.Content', 'galleries_index_show_created')) checked @endif/>
                        <label for="galleries_show_created" style="margin-left: 5px;">
                            Created
                        </label>
                    </span>

                    <span style="margin-right: 10px;">
                        <input class="icheck_input" type="checkbox" id="galleries_index_show_updated" @if(Setting::get('Admin.Content', 'galleries_index_show_updated')) checked @endif/>
                        <label for="galleries_show_updated" style="margin-left: 5px;">
                            Updated
                        </label>
                    </span>
                </td>
            </tr>
            <tr>
                <td>Index page title height</td>
                <td style="vertical-align: middle;">
                    <number-input id="galleries_index_title_height" :init-event=true :init-Min=40 :init-Max=280 :init-value={{ Setting::get('Admin.Content', 'galleries_index_title_height') }}></number-input>
                </td>
            </tr>
            <tr>
                <td>Index page title align</td>
                <td style="vertical-align: middle;">
                    <select class="form-control" id="galleries_index_title_align">
                        <option value="center">center</option>
                        <option value="flex-start">left</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Single page details</td>
                <td style="vertical-align: middle;">
                    <span style="margin-right: 10px;">
                        <input class="icheck_input" type="checkbox" id="galleries_single_show_slug" @if(Setting::get('Admin.Content', 'galleries_single_show_slug')) checked @endif />
                        <label for="galleries_single_show_slug" style="margin-left: 5px;">
                            Slug
                        </label>
                    </span>

                    <span style="margin-right: 10px;">
                        <input class="icheck_input" type="checkbox" id="galleries_single_show_author" @if(Setting::get('Admin.Content', 'galleries_single_show_author')) checked @endif/>
                        <label for="galleries_single_show_author" style="margin-left: 5px;">
                            Author
                        </label>
                    </span>

                    <span style="margin-right: 10px;">
                        <input class="icheck_input" type="checkbox" id="galleries_single_show_access" @if(Setting::get('Admin.Content', 'galleries_single_show_access')) checked @endif/>
                        <label for="galleries_single_show_access" style="margin-left: 5px;">
                            Access
                        </label>
                    </span>

                    <span style="margin-right: 10px;">
                        <input class="icheck_input" type="checkbox" id="galleries_single_show_password" @if(Setting::get('Admin.Content', 'galleries_single_show_password')) checked @endif/>
                        <label for="galleries_single_show_password" style="margin-left: 5px;">
                            Password
                        </label>
                    </span>

                    <span style="margin-right: 10px;">
                        <input class="icheck_input" type="checkbox" id="galleries_single_show_featured_image" @if(Setting::get('Admin.Content', 'galleries_single_show_featured_image')) checked @endif/>
                        <label for="galleries_single_show_featured_image" style="margin-left: 5px;">
                            Featured Image
                        </label>
                    </span>

                    <span style="margin-right: 10px;">
                        <input class="icheck_input" type="checkbox" id="galleries_single_show_gallery_info" @if(Setting::get('Admin.Content', 'galleries_single_show_gallery_info')) checked @endif/>
                        <label for="galleries_single_show_gallery_info" style="margin-left: 5px;">
                            Gallery info
                        </label>
                    </span>
                </td>
            </tr>
    </table>

</div>
