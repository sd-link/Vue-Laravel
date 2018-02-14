<div class="form-group">
    <table class="table">
      <tr>
          <td style="width: 150px;">Background Color</td>
          <td>
              <div id="login_background_color" class="input-group colorpicker-component">
                  <input type="text" value="{{ SiteSetting::getValue('Admin.Login','login_background_color') }}" class="form-control"/>
                  <span class="input-group-addon"><i></i></span>
              </div>
          </td>
      </tr>
      <tr>
          <td style="width: 150px;">Background Image</td>
          <td>
              <div class="input-group">
                  <input id="login_background_image" type="text" value="{{ SiteSetting::getValue('Admin.Login','login_background_image') }}" class="form-control"/>
                  <span class="input-group-addon" style="cursor: pointer; border-left: 0;">Upload</span>
              </div>
          </td>
      </tr>
      <tr>
          <td style="width: 150px;">Logo Text</td>
          <td>
              <input id="login_logo_text" type="text" value="{{ SiteSetting::getValue('Admin.Login','login_logo_text') }}" class="form-control"/>
          </td>
      </tr>
      <tr>
          <td style="width: 150px;">Logo Text Color</td>
          <td>
              <div id="login_logo_text_color" class="input-group colorpicker-component">
                  <input type="text" value="{{ SiteSetting::getValue('Admin.Login','login_logo_text_color') }}" class="form-control"/>
                  <span class="input-group-addon"><i></i></span>
              </div>
          </td>
      </tr>
      <tr>
          <td style="width: 150px;">Logo Image</td>
          <td>
              <div class="input-group">
                  <input id="login_logo_image" type="text" value="{{ SiteSetting::getValue('Admin.Login','login_logo_image') }}" class="form-control"/>
                  <span class="input-group-addon" style="cursor: pointer; border-left: 0;">Upload</span>
              </div>
          </td>
      </tr>
      <tr>
          <td style="width: 150px;">Horisontal Position</td>
          <td>
              <select class="form-control" id="login_horisontal_position">
                  <option value="flex-start">Left</option>
                  <option value="center">Center</option>
                  <option value="flex-end">Right</option>
              </select>
          </td>
      </tr>
    </table>
        <button id="saveLoginSiteSettingsBtn" type="button" name="button" class="btn btn-primary">Save SiteSettings</button>
</div>
