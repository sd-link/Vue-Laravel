<table class="table noselect">
    <tr>
        <td style="width: 20%;"> <div class="form-group" style="padding-top: 5px;"><b>Number of Columns:</b> </td></div>
        <td style="width: 80%;">
            <select id="columns" class="form-control">
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
                <option value="6">6</option>
            </select>
        </td>
    </tr>
    <tr>
        <td> <div class="form-group" style="padding-top: 5px;"><b>Margin between images:</b> </td></div>
        <td>
            <input id="margin" type="text" value="5" placeholder="Default will be used" class="form-control">
        </td>
    </tr>
    <tr>
        <td> <div class="form-group" style="padding-top: 5px;"><b>Display gallery description:</b> </td></div>
        <td>
            <select id="display_description" class="form-control">
                <option value="1" selected>Do not display</option>
                <option value="2">Display below</option>
                <option value="3">Display above</option>
            </select>
        </td>
    </tr>
    <tr>
        <td> <div class="form-group" style="padding-top: 5px;"><b>Display image title:</b> </td></div>
        <td>
            <select id="display_image_title" class=" form-control">
                <option value="1">Yes</option>
                <option value="2" selected>No</option>
            </select>
            <div>
                <small>Display image title below image.</small>
            </div>
        </td>
    </tr>
    <tr>
        <td> <div class="form-group" style="padding-top: 5px;"><b>Display image caption:</b> </td></div>
        <td>
            <select id="display_image_caption" class="form-control">
                <option value="1">Yes</option>
                <option value="2" selected>No</option>
            </select>
            <div>
                <small>Display image caption below image.</small>
            </div>
        </td>
    </tr>
    <tr>
        <td> <div class="form-group" style="padding-top: 5px;"><b>Sorting:</b> </td></div>
        <td>
            <select id="sorting" class="display_description form-control">
                <option value="1" selected>Custom Sort</option>
                <option value="2">Random Sort</option>
                <option value="3">Upload Date</option>
            </select>
            <div>
                <small>Custom sort shows images the way you order them.</small>
            </div>
        </td>
    </tr>
    <tr>
        <td> <div class="form-group" style="padding-top: 5px;"><b>Pagination:</b> </td></div>
        <td>
            <select id="pagination" class="form-control">
                <option value="1" selected>Yes</option>
                <option value="2">No</option>
            </select>
            <div>
                <small>Only functional on gallery pages, not when using shortcodes.</small>
            </div>
        </td>
    </tr>
    <tr>
        <td> <div class="form-group" style="padding-top: 5px;"><b>Images per page:</b> </td></div>
        <td>
            <input id="images_per_page" type="text" value="12" placeholder="Default will be used" class="form-control">
            <div>
                <small>How many images to show when using pagination.</small>
            </div>
        </td>
    </tr>
    <tr>
        <td> <div class="form-group" style="padding-top: 5px;"><b>Lightbox:</b></td></div>
        <td>
            <select id="lightbox" class="form-control">
                <option value="1" selected>Yes</option>
                <option value="2">No</option>
            </select>
            <div>
                <small>Gallery looks better with lightbox, use it!</small>
            </div>
        </td>
    </tr>
</table>
