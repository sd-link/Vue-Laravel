
    <div class="modal" id="editUserModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">Edit User</h4>
                </div>
                <form action="{{route('backend.users.update')}}" method="POST" id="editUserForm">
                  {{ csrf_field() }}
                  <div id="editUserModalBody" class="modal-body">
                      <input type="hidden" value="" id="id" name="id" class="form-control userid">
                      <div class="form-group">
                          <div class="nav-tabs-custom">
                              <ul class="nav nav-tabs">
                                <li class="active"><a data-toggle="tab" href="#editUserTab" aria-expanded="true">Basics</a></li>
                                <li class=""><a data-toggle="tab" href="#rolesTab" aria-expanded="false">Roles</a></li>
                                <li class=""><a id="preview" data-toggle="tab" href="#passwordTab" aria-expanded="false">Password</a></li>
                              </ul>
                              <div class="tab-content">
                                  <!-- /.POST BODY -->
                                <div id="editUserTab" class="tab-pane active">
                                    <div>
                                        <div class="form-group firstname-group" style="width: 50%; float:left; padding-right: 5px;">
                                            <label for="firstname">First Name</label>
                                            <input id="firstname" type="text" placeholder="Enter first name" value="" name="firstname" class="form-control firstname">

                                            <span class="help-block">
                                                <small class="firstname-feedback"></small>
                                            </span>
                                        </div>

                                        <div class="form-group lastname-group" style="width: 50%; float:left;">
                                            <label for="lastname">Last Name</label>
                                            <input id="lastname" type="text" placeholder="Enter last name" value="" name="lastname" class="form-control lastname">

                                            <span class="help-block">
                                                <small class="lastname-feedback"></small>
                                            </span>
                                        </div>

                                        <div style="clear: both;"></div>
                                    </div>

                                    <div>
                                        <div class="form-group username-group" style="width: 50%; float:left; padding-right: 5px;">
                                            <label for="username">Username</label>
                                            <input id="username" type="text" placeholder="Enter username" value="" name="username" class="form-control username">

                                            <span class="help-block">
                                                <small class="username-feedback"></small>
                                            </span>

                                        </div>

                                        <div class="form-group email-group" style="width: 50%; float:left;">
                                            <label for="email">Email</label>
                                            <input id="email" type="text" placeholder="Enter email" value="" name="email" class="form-control email">

                                            <span class="help-block">
                                                <small class="email-feedback"></small>
                                            </span>
                                        </div>

                                        <div style="clear: both;"></div>
                                    </div>

                                    <div class="form-group bio-group" >
                                        <label for="bio">Bio</label>
                                        <textarea rows="3" name="bio" id="bio" class="form-control bio" style="resize: vertical;"></textarea>

                                        <span class="help-block">
                                            <small class="bio-feedback"></small>
                                        </span>
                                    </div>
                                </div>
                                <!-- /.tab-pane -->
                                <div id="rolesTab" class="tab-pane">
                                  <div class="form-group" >
                                      <label for="roles">Roles</label>
                                      <div id="roles" class="user_roles">
                                      </div>
                                  </div>

                                  <div class="form-group" >
                                      <label for="roles">Permissions</label>
                                      <div class="permissions">
                                      </div>
                                  </div>

                                  <div class="form-group" >
                                      <label for="roles">Extra Permissions</label>
                                      <div class="extra_permissions">
                                      </div>
                                  </div>

                                </div>
                                <!-- /.tab-pane -->
                                <div id="passwordTab" class="tab-pane">
                                  <div class="form-group" >
                                          <div class="form-group password-group" >
                                              <label for="password">Password</label>
                                              <input id="password" type="password" value="" name="password" class="form-control password">

                                              <span class="help-block">
                                                  <small class="password-feedback"></small>
                                              </span>
                                          </div>

                                          <div class="form-group password-confirm-group" >
                                              <label for="password-confirm">Confirm Password</label>
                                              <input id="password-confirm" type="password" value="" name="password-confirm" class="form-control password-confirm">

                                              <span class="help-block">
                                                  <small class="password-confirm-feedback"></small>
                                              </span>
                                          </div>
                                      {{-- </div> --}}
                                  </div>
                                </div>
                                <!-- /.tab-pane -->
                              </div>
                              <!-- /.tab-content -->
                          </div>
                      </div>
                  </div>
                </form>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Cancel</button>
                    <button id="editUserBtn" type="button" class="btn btn-primary pull-right"></i> Save</button>
                </div>
            </div>
          <!-- /.modal-content -->
        </div>
      <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->


    @push('scripts')
    <script>

      // Edit User Modal
      $('#editUserModal').on('show.bs.modal', function(e) {

          // $('.modal .modal-body').css('overflow-y', 'auto');
          // $('.modal .modal-body').css('max-height', $(window).height() * 0.65);

          var $modal = $(this);
          var url = e.relatedTarget.getAttribute('data-url');

          $.ajax(url, {
              method: 'GET',
              dataType:'json',
              success: function(result) {
                  console.log('success' + result.user.bio);
                  // $modal.find('.edit-content').html(data);
                  $modal.find('#firstname').val(result.user.firstname);
                  $modal.find('#lastname').val(result.user.lastname);
                  $modal.find('#username').val(result.user.username);
                  $modal.find('#bio').val(result.user.bio);
                  $modal.find('#email').val(result.user.email);
                  $modal.find('#id').val(result.user.id);

                  var rolesMarkup = "";
                  var roles = [];

                  var permissionsMarkup = {};
                  var allPermissions = {};
                  var userData = result.user;

                  // build checkboxes for roles and permissions
                  $.each( result.roles, function( key, role ) {
                      // roles checkboxes
                      rolesMarkup += `<input class="icheck_input" id='role-`+role.id+`' type='checkbox' value=`+role.id+` name="userRoles[]">`+`<label for='role-`+role.id+`' class='noselect'>`+role.display_name+`</label>`;

                      permissionsMarkup['role-'+role.id] = {};

                      // permissions checkboxes
                      $.each( role.permissions, function( key2, permission ) {
                          permissionsMarkup['role-'+role.id][permission.id] = `<input class="icheck_input" checked="checked" disabled="true" id='permission-`+permission.id+`' value=`+permission.id+` type='checkbox' name="permissions[]"><label for='permission-`+permission.id+`'>`+permission.display_name+`</label></snap>`;
                      });
                  });

                  // Initiate all permissions
                  $.each( result.permissions, function( key3, permission ) {
                      allPermissions[permission.id] = `<input class="icheck_input" id='permission-`+permission.id+`' value=`+permission.id+` type='checkbox' name="extrapermissions[]"><label for='permission-`+permission.id+`'>`+permission.display_name+`</label></snap>`;
                  });

                  // Show available roles on the page
                  $modal.find('#roles').html(rolesMarkup);

                  // $modal.find('#roles').html(rolesMarkup);

                  $.each( result.user.roles, function( key, role ) {
                      console.log(role.id);
                      if (roles.indexOf(role.id)==-1) {
                          $('#role-' + role.id).attr('checked', 'checked');
                          // $('#role-' + role.id).attr('disabled', 'true');
                          roles.push(role.id);
                      }
                  });

                    // get all roles checkboxes
                    var rolesCheckboxes = document.forms['editUserForm'].elements[ 'userRoles[]' ];

                    // attach onclick for all checkboxes and show combined permissions for each role that is selected
                    $.each( result.roles, function( key, role ) {
                        $('#role-'+role.id).on("click", function(e){
                            initCheckboxes();
                        });
                    });

                    initCheckboxes();

                    function initCheckboxes() {
                        var rolesToMerge = [];
                        var permissions = [];

                        for (var i=0; i < rolesCheckboxes.length; i++) {
                            if(rolesCheckboxes[i].checked) {
                                rolesToMerge.push(permissionsMarkup[rolesCheckboxes[i].id]);
                            }
                        }

                        var selectedRoles = {};
                        for (i = 0; i < rolesToMerge.length; i++) {
                            $.extend(selectedRoles, rolesToMerge[i]);
                        }

                        // insert permissions on the page
                        $('#editUserForm').find('.permissions').html('');
                        for (var key in selectedRoles) {
                            $('#editUserForm').find('.permissions').append(selectedRoles[key]);
                        }

                        $('#editUserForm').find('.extra_permissions').html('');
                        $.each(allPermissions, function(index, value){
                             if(!selectedRoles[index]) {
                                $('#editUserForm').find('.extra_permissions').append(value);
                            }
                        });

                      $.each( userData.permissions, function( key, permission ) {
                          if (permissions.indexOf(permission.id)==-1) {
                              $('#permission-' + permission.id).attr('checked', 'checked');
                              permissions.push(permission.id);
                          }
                      });
                    }

                  // var defaultPermissionsMarkup = "";
                  // var extraPermissionsMarkup = "";
                  // var defaultPermissions = [];

                  // $.each( result.permissions, function( key, value ) {
                  //     permissionsMarkup += `<snap style='display: inline-block; margin-right: 20px;' class='checkbox'><label><input id='permission-`+value.id+`' type='checkbox' name="permissions">`+value.display_name+`</label></snap>`;
                  // });

                  // // LIST USER PERMISSIONS GIVEN BY ROLES
                  // $.each( result.user.roles, function( key, val ) {
                  //     $.each( val.permissions, function( key2, permission ) {
                  //         if (defaultPermissions.indexOf(permission.id)==-1) {
                  //         //     $('#permission-' + permission.id).attr('checked', 'checked');
                  //         //     $('#permission-' + permission.id).attr('disabled', 'true');
                  //             defaultPermissionsMarkup += `<snap style='display: inline-block; margin-right: 20px;' class='checkbox'><label><input checked="checked" disabled="true" id='permission-`+permission.id+`' type='checkbox' name="permissions">`+permission.display_name+`</label></snap>`;

                  //             defaultPermissions.push(permission.id);
                  //         }
                  //     });
                  // });

                  // $modal.find('#permissions').html(defaultPermissionsMarkup);

                  // // LIST USER EXTRA PERMISSIONS
                  // $.each( result.permissions, function( key, permission ) {
                  //     if (defaultPermissions.indexOf(permission.id)==-1) {
                  //         extraPermissionsMarkup += `<snap style='display: inline-block; margin-right: 20px;' class='checkbox'><label><input id='permission-`+permission.id+`' value=`+permission.id+` type='checkbox' name="extrapermissions">`+permission.display_name+`</label></snap>`;
                  //     }
                  // });

                  // $modal.find('#extraPermissions').html(extraPermissionsMarkup);

                  // $.each( result.user.permissions, function( key, permission ) {
                  //     $('#permission-' + permission.id).attr('checked', 'checked');
                  // });

              },
              error: function(result) {
                  var errors = result.responseJSON;
                  if(typeof errors !== 'undefined') {
                      $modal.find('#editUserModalBody').html(errors.message);
                  } else {
                      $modal.find('#editUserModalBody').html('Something went wrong, user data could not be loaded.');
                  }
              }
          });
      })

      $("#editUserBtn").on("click", function(e){

          var selectedRoles = [];
          var selectedPermissions = [];
          $('#roles input[type="checkbox"]:checked').each(function () {
              selectedRoles.push($(this).val());
          });

          $('#editUserForm').find('.extra_permissions input[type="checkbox"]:checked').each(function () {
              console.log('abc: '+$(this).val())
              selectedPermissions.push($(this).val());
          });
          // var editUserForm = $("#editUserForm");
          // var formData = editUserForm.serialize();
          $.ajax('{{route('backend.users.update')}}', {
              method: 'POST',
              dataType:'json',
              data: {
                  id: $('#id').val(),
                  username: $('#username').val(),
                  email: $('#email').val(),
                  firstname: $('#firstname').val(),
                  lastname: $('#lastname').val(),
                  bio: $('#bio').val(),
                  password: $('#password').val(),
                  password_confirmation: $('#password-confirm').val(),
                  roles: selectedRoles,
                  extrapermissions: selectedPermissions,
                  _token: Laravel.csrfToken
              },

              success: function(result) {
                  clearEditFormErrors();
                  $('#editUserModal').modal('hide');

                  $('#row'+$('#id').val()).find('.name').html($.trim(result.user.firstname)+' '+$.trim(result.user.lastname));
                  $('#row'+$('#id').val()).find('.username').html($.trim(result.user.username));
                  $('#row'+$('#id').val()).find('.email').html(result.user.email);

                  var rolesMarkup ="";
                  var rolesLength = result.user.roles.length;

                  $.each( result.user.roles, function( key, role ) {
                      if (key === rolesLength - 1) {
                          rolesMarkup += role.display_name;
                      } else {
                          rolesMarkup += role.display_name + ", ";
                      }
                  });

                  $('#row'+$('#id').val()).find('.roles').html(rolesMarkup);

                  var extraPermissionsMarkup ="";
                  var permissionsLength = result.user.permissions.length;

                  $.each( result.user.permissions, function( key, permission ) {
                      if (key === permissionsLength - 1) {
                          extraPermissionsMarkup += permission.display_name;
                      } else {
                          extraPermissionsMarkup += permission.display_name + ", ";
                      }
                  });

                  $('#row'+$('#id').val()).find('.permissions').html(extraPermissionsMarkup);

                  $('#id').val('');
                  $('#firstname').val('');
                  $('#lastname').val('');
                  $('#username').val('');
                  $('#bio').val('');
                  $('#email').val('');
                  $('#password').val('');
                  $('#password-confirm').val('');

              },
              error: function(result) {
                  var errors = result.responseJSON;
                  clearEditFormErrors();
                  if(typeof errors !== 'undefined') {
                      if(typeof errors.firstname !== 'undefined'){
                          $('#editUserTab').find('.firstname-group').addClass('has-error');
                          $('#editUserTab').find('.firstname-feedback').html(errors.firstname);
                      }

                      if(typeof errors.lastname !== 'undefined'){
                          $('#editUserTab').find('.lastname-group').addClass('has-error');
                          $('#editUserTab').find('.lastname-feedback').html(errors.lastname);
                      }

                      if(typeof errors.username !== 'undefined'){
                          $('#editUserTab').find('.username-group').addClass('has-error');
                          $('#editUserTab').find('.username-feedback').html(errors.username);
                      }

                      if(typeof errors.email !== 'undefined'){
                          $('#editUserTab').find('.email-group').addClass('has-error');
                          $('#editUserTab').find('.email-feedback').html(errors.email);
                      }

                      if(typeof errors.bio !== 'undefined'){
                          $('#editUserTab').find('.bio-group').addClass('has-error');
                          $('#editUserTab').find('.bio-feedback').html(errors.bio);
                      }

                      if(typeof errors.password !== 'undefined'){
                          $('#passwordTab').find('.password-group').addClass('has-error');
                          $('#passwordTab').find('.password-feedback').html(errors.password[0]);
                          if(typeof errors.password[1] !== 'undefined'){
                              $('#passwordTab').find('.password-confirm-group').addClass('has-error');
                              $('#passwordTab').find('.password-confirm-feedback').html(errors.password[1]);
                          }
                      }

                      $('#editUserModalBody').html(errors.message);
                  } else {
                      $('#editUserModalBody').html('Something went wrong, user data could not be updated.');
                  }
              }
          });
      });

      function clearEditFormErrors()
      {
          $('#editUserTab').find('.firstname-group').removeClass('has-error');
          $('#editUserTab').find('.lastname-group').removeClass('has-error');
          $('#editUserTab').find('.username-group').removeClass('has-error');
          $('#editUserTab').find('.email-group').removeClass('has-error');
          $('#editUserTab').find('.bio-group').removeClass('has-error');
          $('#editUserTab').find('.firstname-feedback').html('');
          $('#editUserTab').find('.lastname-feedback').html('');
          $('#editUserTab').find('.username-feedback').html('');
          $('#editUserTab').find('.email-feedback').html('');
          $('#editUserTab').find('.bio-feedback').html('');

          $('#passwordTab').find('.password-group').removeClass('has-error');
          $('#passwordTab').find('.password-feedback').html('');

          $('#passwordTab').find('.password-confirm-group').removeClass('has-error');
          $('#passwordTab').find('.password-confirm-feedback').html('');

      }

    </script>
    @endpush
