<table id="fieldOptions" class="table noselect" style="display: none;">
    <tr>
        <td style="width: 80px;"><label>Label</label></td>
        <td><span id="label" style="display: none;"></span><input id="editLabel" type="text" name="" value="" style="display: none;"></td>
    </tr>
    <tr>
        <td><label>Required</label></td>
        <td>
            <div class="checkbox icheck">
                <label>
                   <input id="required" type="checkbox" name="remember">
               </label>
           </div>
       </td>
    </tr>
    <tr>
        <td style="width: 80px;"><label>Id</label></td>
        <td><input id="fieldId" type="text" name="" value="">
            <small id="must_be_unique">Must be unique and not use reserved ids, only english characters allowed.</small>
        </td>
    </tr>
    <tr>
        <td style="width: 80px;"><label>Placeholder</label></td>
        <td><input id="fieldPlaceholder" type="text" name="" value=""></td>
    </tr>
    <tr style="display: none;">
        <td style="width: 80px;"><label>Value</label></td>
        <td>
            <input style="width: 98px;" id="select_value_input" type="text"> <button style="width: 55px;" id="add_select_value_btn" data-action="add">Add</button>
            <br>
            <div  style="padding: 15px; padding-left: 0px;">

                <table id="select_added_values">

                </table>

            </div>
        </td>
    </tr>
</table>

<div id="fieldOptionsEmpty" class="form-group" style="margin-top: 10px;">
    <h4>Select a field to see options.</h4>
</div>
