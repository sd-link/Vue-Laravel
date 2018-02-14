
    <div class="modal" id="createUserModal">
        <div class="modal-dialog">
            <div class="modal-content" >
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">Create User</h4>
                </div>
                <form action="{{route('backend.users.create')}}" method="POST" id="createUserForm">
                  {{ csrf_field() }}
                  <div id="createUserModalBody" class="modal-body scrollbar-inner" >
                                  <!-- /.POST BODY -->
                                <div id="createUserTab">
                                    <div>
                                        <div class="form-group firstname-group" style="width: 50%; float:left; padding-right: 5px;">
                                            <label for="firstname">First Name</label>
                                            <input type="text" placeholder="Enter first name" value="" name="firstname" class="form-control firstname">

                                            <span class="help-block">
                                                <small class="firstname-feedback"></small>
                                            </span>
                                        </div>

                                        <div class="form-group lastname-group" style="width: 50%; float:left;">
                                            <label for="lastname">Last Name</label>
                                            <input type="text" placeholder="Enter last name" value="" name="lastname" class="form-control lastname">

                                            <span class="help-block">
                                                <small class="lastname-feedback"></small>
                                            </span>
                                        </div>

                                        <div style="clear: both;"></div>
                                    </div>

                                    <div>
                                        <div class="form-group username-group" style="width: 50%; float:left; padding-right: 5px;">
                                            <label for="username">Username</label>
                                            <input type="text" placeholder="Enter username" value="" name="username" class="form-control username">

                                            <span class="help-block">
                                                <small class="username-feedback"></small>
                                            </span>

                                        </div>

                                        <div class="form-group email-group" style="width: 50%; float:left;">
                                            <label for="email">Email</label>
                                            <input type="text" placeholder="Enter email" value="" name="email" class="form-control email">

                                            <span class="help-block">
                                                <small class="email-feedback"></small>
                                            </span>
                                        </div>

                                        <div style="clear: both;"></div>
                                    </div>

                                    <div class="form-group bio-group" >
                                        <label for="bio">Bio</label>
                                        <textarea rows="3" name="bio" class="form-control bio" style="resize: vertical;"></textarea>

                                        <span class="help-block">
                                            <small class="bio-feedback"></small>
                                        </span>
                                    </div>

                                    <div class="form-group roles-group" >
                                        <label for="roles">Roles</label>
                                        <div class="roles">
                                        </div>

                                       <span class="help-block">
                                            <small class="roles-feedback"></small>
                                        </span>
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

                                  <div class="form-group" >
                                          <div class="form-group password-group" >
                                              <label for="password">Password</label>
                                              <input type="password" value="" name="password" class="form-control password">

                                              <span class="help-block">
                                                  <small class="password-feedback"></small>
                                              </span>
                                          </div>

                                          <div class="form-group password-confirm-group" >
                                              <label for="password-confirm">Confirm Password</label>
                                              <input type="password" value="" name="password-confirm" class="form-control password-confirm">

                                              <span class="help-block">
                                                  <small class="password-confirm-feedback"></small>
                                              </span>
                                          </div>
                                      {{-- </div> --}}
                                  </div>
                                </div>

                  </div>
                </form>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Cancel</button>
                    <button id="createUserBtn" type="button" class="btn btn-primary pull-right"><i class="fa fa-plus" aria-hidden="true"></i> Create</button>
                </div>
            </div>
          <!-- /.modal-content -->
        </div>
      <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->


    @push('scripts')
      <script>
        // Create User Modal
        $('#createUserModal').on('show.bs.modal', function () {
            var $modal = $(this);
            $('.modal .modal-body').css('overflow-y', 'auto'); 
            $('.modal .modal-body').css('max-height', $(window).height() * 0.7);

            $.ajax('{{ route('backend.users.getallroles') }}', {
                method: 'GET',
                dataType:'json',
                success: function(result) {
                    var rolesMarkup = "";
                    var permissionsMarkup = {};
                    var allPermissions = {};

                    // build checkboxes for roles and permissions
                    $.each( result.roles, function( key, role ) {
                        // roles checkboxes
                        rolesMarkup += `<snap style='display: inline-block; margin-right: 20px;' class='checkbox'><label class='noselect'><input id='addrole-`+role.id+`' type='checkbox' value=`+role.id+` name="userRoles[]">`+role.display_name+`</label></snap>`;
                        
                        permissionsMarkup['addrole-'+role.id] = {};

                        // permissions checkboxes
                        $.each( role.permissions, function( key2, permission ) {
                            permissionsMarkup['addrole-'+role.id][permission.id] = `<snap style='display: inline-block; margin-right: 20px;' class='checkbox'><label><input checked="checked" disabled="true" id='permission-`+permission.id+`' value=`+permission.id+` type='checkbox' name="permissions[]">`+permission.display_name+`</label></snap>`;
                        });
                    });

                    // Initiate all permissions
                    $.each( result.permissions, function( key3, permission ) {
                        allPermissions[permission.id] = `<snap style='display: inline-block; margin-right: 20px;' class='checkbox'><label><input id='permission-`+permission.id+`' value=`+permission.id+` type='checkbox' name="extrapermissions[]">`+permission.display_name+`</label></snap>`;
                    });

                    // Show available roles on the page
                    $modal.find('.roles').html(rolesMarkup);

                    // get all roles checkboxes
                    var rolesCheckboxes = document.forms['createUserForm'].elements[ 'userRoles[]' ];

                    // attach onclick for all checkboxes and show combined permissions for each role that is selected
                    $.each( result.roles, function( key, role ) {
                        $('#addrole-'+role.id).on("click", function(e){
                            var rolesToMerge = [];

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
                            $('#createUserForm').find('.permissions').html('');
                            for (var key in selectedRoles) {
                                $('#createUserForm').find('.permissions').append(selectedRoles[key]);
                            }

                            $('#createUserForm').find('.extra_permissions').html('');
                            $.each(allPermissions, function(index, value){
                                 if(!selectedRoles[index]) {
                                    $('#createUserForm').find('.extra_permissions').append(value);
                                }
                            });
                        });
                    });

                },
                error: function(result) {
                    var errors = result.responseJSON;
                    if(typeof errors !== 'undefined') {
                        console.log(errors.message);
                    } else {
                        console.log('Something went wrong while fetching user roles.');
                    }
                }
            });

        });

        $("#createUserBtn").on("click", function(e){
            var selectedRoles = [];
            var selectedPermissions = [];
            $('#createUserForm').find('.roles input[type="checkbox"]:checked').each(function () {
                selectedRoles.push($(this).val());
            });

            $('#createUserForm').find('.extra_permissions input[type="checkbox"]:checked').each(function () {
                selectedPermissions.push($(this).val());
            });
            // var editUserForm = $("#editUserForm");
            // var formData = editUserForm.serialize();
            $.ajax('{{route('backend.users.create')}}', {
                method: 'POST',
                dataType:'json',
                data: {
                    username: $('#createUserForm').find('.username').val(),
                    email: $('#createUserForm').find('.email').val(),
                    firstname: $('#createUserForm').find('.firstname').val(),
                    lastname: $('#createUserForm').find('.lastname').val(),
                    bio: $('#createUserForm').find('.bio').val(),
                    password: $('#createUserForm').find('.password').val(),
                    password_confirmation: $('#createUserForm').find('.password-confirm').val(),
                    roles: selectedRoles,
                    permissions: selectedPermissions,
                    _token: Laravel.csrfToken
                },

                success: function(result) {
                    clearCreateFormErrors();
                    $('#createUserModal').modal('hide');
                    location.reload();
                },
                error: function(result) {
                    var errors = result.responseJSON;
                    clearCreateFormErrors();

                    if(typeof errors !== 'undefined') {
                        if(typeof errors.firstname !== 'undefined'){
                            $('#createUserForm').find('.firstname-group').addClass('has-error');
                            $('#createUserForm').find('.firstname-feedback').html(errors.firstname);
                        }

                        if(typeof errors.lastname !== 'undefined'){
                            $('#createUserForm').find('.lastname-group').addClass('has-error');
                            $('#createUserForm').find('.lastname-feedback').html(errors.lastname);
                        }

                        if(typeof errors.username !== 'undefined'){
                            $('#createUserForm').find('.username-group').addClass('has-error');
                            $('#createUserForm').find('.username-feedback').html(errors.username);
                        }

                        if(typeof errors.email !== 'undefined'){
                            $('#createUserForm').find('.email-group').addClass('has-error');
                            $('#createUserForm').find('.email-feedback').html(errors.email);
                        }

                        if(typeof errors.bio !== 'undefined'){
                            $('#createUserForm').find('.bio-group').addClass('has-error');
                            $('#createUserForm').find('.bio-feedback').html(errors.bio);
                        }

                        if(typeof errors.roles !== 'undefined'){
                            $('#createUserForm').find('.roles-group').addClass('has-error');
                            $('#createUserForm').find('.roles-feedback').html(errors.roles);
                        }

                        if(typeof errors.password !== 'undefined'){
                            $('#createUserForm').find('.password-group').addClass('has-error');
                            $('#createUserForm').find('.password-feedback').html(errors.password[0]);
                            if(typeof errors.password[1] !== 'undefined'){
                                $('#createUserForm').find('.password-confirm-group').addClass('has-error');
                                $('#createUserForm').find('.password-confirm-feedback').html(errors.password[1]);
                            }
                        }

                        $('#createUserModalBody').html(errors.message);
                    } else {
                        $('#createUserModalBody').html('Something went wrong, user could not be created.');
                    }
                }
            });
        });

        function clearCreateFormErrors()
        {
            $('#createUserForm').find('.firstname-group').removeClass('has-error');
            $('#createUserForm').find('.lastname-group').removeClass('has-error');
            $('#createUserForm').find('.username-group').removeClass('has-error');
            $('#createUserForm').find('.email-group').removeClass('has-error');
            $('#createUserForm').find('.bio-group').removeClass('has-error');
            $('#createUserForm').find('.roles-group').removeClass('has-error');

            $('#createUserForm').find('.firstname-feedback').html('');
            $('#createUserForm').find('.lastname-feedback').html('');
            $('#createUserForm').find('.username-feedback').html('');
            $('#createUserForm').find('.email-feedback').html('');
            $('#createUserForm').find('.bio-feedback').html('');
            $('#createUserForm').find('.roles-feedback').html('');

            $('#createUserForm').find('.password-group').removeClass('has-error');
            $('#createUserForm').find('.password-feedback').html('');

            $('#createUserForm').find('.password-confirm-group').removeClass('has-error');
            $('#createUserForm').find('.password-confirm-feedback').html('');

        }
      </script>
    @endpush


