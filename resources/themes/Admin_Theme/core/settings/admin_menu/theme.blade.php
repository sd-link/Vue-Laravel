<div class="form-group" id="abcd">
  <div class="form-group">
      <table class="table">
          <tr>
              <td style="width: 150px;">Layout</td>
              <td class="layout">
                  <label style="margin-right: 10px;">
                    <input type="radio" style="display: none;" name="layout" value="sidebar" />
                    <img src="http://larapress.dev/images/admin/default_theme.png" style="width: 220px; height: auto;">
                    <div class="text-center" style="margin-top: 7px;">
                        With Sidebar
                    </div>
                  </label>
                  <label>
                    <input type="radio" name="layout" value="topnav-wide" />
                    <img src="http://larapress.dev/images/admin/navbar.png" style="width: 220px; height: auto;">
                    <div class="text-center" style="margin-top: 7px;">
                        With Navbar Wide
                    </div>
                  </label>
                  <label>
                    <input type="radio" name="layout" value="topnav-boxed" />
                    <img src="http://larapress.dev/images/admin/navbar_boxed.png" style="width: 220px; height: auto;">
                    <div class="text-center" style="margin-top: 7px;">
                        With Navbar Boxed
                    </div>
                  </label>
              </td>
          </tr>
          <tr>
              <td style="width: 150px;">Theme</td>
              <td>
                  <div>No theme selected, you have customized font&colors. Selecting a theme again and saving will reset your customizations.</div>
                  <label style="margin-right: 10px;">
                      <input type="radio" name="theme" value="default" />
                      <img src="http://larapress.dev/images/admin/default_theme.png" style="width: 220px; height: auto;">
                      <div class="text-center" style="margin-top: 7px;">
                          Default Theme
                      </div>
                  </label>

                  <label style="margin-right: 10px;">
                      <input type="radio" name="theme" value="dark" />
                      <img src="http://larapress.dev/images/admin/dark_theme.png" style="width: 220px; height: auto;">
                      <div class="text-center" style="margin-top: 7px;">
                          Dark Theme
                      </div>
                  </label>

                  <label>
                      <input type="radio" name="theme" value="bright" />
                      <img src="http://larapress.dev/images/admin/bright_theme.png" style="width: 220px; height: auto;">
                      <div class="text-center" style="margin-top: 7px;">
                          Bright Theme
                      </div>
                  </label>
              </td>
          </tr>
      </table>
      <button id="saveThemeSettingsBtn" type="button" name="button" class="btn btn-primary">Save Settings</button>
  </div>
</div>
