<div class="form-group" style="padding: 12px;">
    <label for="">Main Menu</label>
    <div class="form-group">
        <table class="table">
            <tr>
                <td style="width: 150px;">Font Size</td>
                <td>
                    <number-input id="main_menu_font_size" :init-event=true :init-Min=12 :init-Max=22 :init-value={{(int)$settings->main_menu_font_size}}></number-input>
                </td>
            </tr>
            <tr>
                <td style="width: 150px;">Font Color</td>
                <td>
                    <color-picker id="main_menu_font_color" init-color="{{$settings->main_menu_font_color}}"></color-picker>
                </td>
            </tr>
            <tr>
                <td style="width: 150px;">Font Hover Color</td>
                <td>
                    <color-picker id="main_menu_font_hover_color" init-color="{{$settings->main_menu_font_hover_color}}"></color-picker>
                </td>
            </tr>
            <tr>
                <td>Main Background Color</td>
                <td>
                    <color-picker id="main_menu_main_background_color" init-color="{{$settings->main_menu_main_background_color}}"></color-picker>
                </td>
            </tr>
            <tr>
                <td>Sub Background Color</td>
                <td>
                    <color-picker id="main_menu_sub_background_color" init-color="{{$settings->main_menu_sub_background_color}}"></color-picker>
                </td>
            </tr>
            <tr>
                <td>Menu Hover Color</td>
                <td>
                    <color-picker id="main_menu_menu_hover_color" init-color="{{$settings->main_menu_menu_hover_color}}"></color-picker>
                </td>
            </tr>
        </table>
    </div>

    <label for="">Content</label>
    <div class="form-group">
        <table class="table">
            <tr>
                <td style="width: 150px;">Font Color</td>
                <td>
                    <color-picker id="content_font_color" init-color="{{$settings->content_font_color}}"></color-picker>
                </td>
            </tr>
            <tr>
                <td style="width: 150px;">Background Color</td>
                <td>
                    <color-picker id="content_background_color" init-color="{{$settings->content_background_color}}"></color-picker>
                </td>
            </tr>
        </table>
    </div>

    <label for="">Box</label>
    <div class="form-group">
        <table class="table">
            <tr>
                <td style="width: 150px;">Background Color</td>
                <td>
                    <color-picker id="content_box_background_color" init-color="{{$settings->content_box_background_color}}"></color-picker>
                </td>
            </tr>
            <tr>
                <td style="width: 150px;">Header&Footer Border</td>
                <td>
                    <color-picker id="content_box_header_footer_border_color" init-color="{{$settings->content_box_header_footer_border_color}}"></color-picker>
                </td>
            </tr>
        </table>
    </div>

    <label for="">Tabs</label>
    <div class="form-group">
        <table class="table">
            <tr>
                <td style="width: 150px;">Menu Color</td>
                <td>
                    <color-picker id="content_tabs_menu_background_color" init-color="{{$settings->content_tabs_menu_background_color}}"></color-picker>
                </td>
            </tr>
            <tr>
                <td style="width: 150px;">Menu Bottom Border</td>
                <td>
                    <color-picker id="content_tabs_menu_bottom_border_color" init-color="{{$settings->content_tabs_menu_bottom_border_color}}"></color-picker>
                </td>
            </tr>
            <tr>
                <td style="width: 150px;">Background Color</td>
                <td>
                    <color-picker id="content_tabs_background_color" init-color="{{$settings->content_tabs_background_color}}"></color-picker>
                </td>
            </tr>
            {{-- <tr>
                <td style="width: 150px;">Top Border Size</td>
                <td>
                    <number-input id="content_tabs_active_top_border_size" :init-Min=1 :init-Max=3 :init-value="{{(int)$settings->content_tabs_active_top_border_size}}"></number-input>
                </td>
            </tr> --}}
            <tr>
                <td style="width: 150px;">Top Border Color</td>
                <td>
                    <color-picker id="content_tabs_top_border_color" init-color="{{$settings->content_tabs_top_border_color}}"></color-picker>
                </td>
            </tr>
            <tr>
                <td style="width: 150px;">Sides Border Color</td>
                <td>
                    <color-picker id="content_tabs_sides_border_color" init-color="{{$settings->content_tabs_sides_border_color}}"></color-picker>
                </td>
            </tr>
        </table>
    </div>

    <label for="">Input</label>
    <div class="form-group">
        <table class="table">
            <tr>
                <td style="width: 150px;">Font Color</td>
                <td>
                    <color-picker id="content_input_font_color" init-color="{{$settings->content_input_font_color}}"></color-picker>
                </td>
            </tr>
            <tr>
                <td style="width: 150px;">Background Color</td>
                <td>
                    <color-picker id="content_input_background_color" init-color="{{$settings->content_input_background_color}}"></color-picker>
                </td>
            </tr>
            <tr>
                <td style="width: 150px;">Border Color</td>
                <td>
                    <color-picker id="content_input_border_color" init-color="{{$settings->content_input_border_color}}"></color-picker>
                </td>
            </tr>
            <tr>
                <td style="width: 150px;">Addon Font Color</td>
                <td>
                    <color-picker id="content_input_addon_font_color" init-color="{{$settings->content_input_addon_font_color}}"></color-picker>
                </td>
            </tr>
            <tr>
                <td style="width: 150px;">Addon Background Color</td>
                <td>
                    <color-picker id="content_input_addon_background_color" init-color="{{$settings->content_input_addon_background_color}}"></color-picker>
                </td>
            </tr>
            <tr>
                <td style="width: 150px;">Addon Border Color</td>
                <td>
                    <color-picker id="content_input_addon_border_color" init-color="{{$settings->content_input_addon_border_color}}"></color-picker>
                </td>
            </tr>
        </table>
    </div>

    <button id="saveCustomCssBtn" type="button" name="button" class="btn btn-primary">Save Settings</button>
</div>
