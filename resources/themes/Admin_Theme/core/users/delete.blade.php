
    <div class="modal" id="deleteUserModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">Delete User</h4>
                </div>
                <form action="{{route('backend.users.update')}}" method="POST" id="deleteUserForm">
                  {{ csrf_field() }}
                  <div class="modal-body">
                      <input type="hidden" value="" name="id" class="form-control userid">

                      <div class="form-group">
                            <span>You are about to delete user 
                              <span id="userToDeleteUsername" style="color: #00c0ef; border-bottom: 1px dashed #00c0ef;"></span>
                              <span id="userToDeleteName"></span>
                            </span>
                      </div>

                      <div class="form-group">This action cannot be reversed. What should we do with this user's posts?</div>
                      <div class="form-group" style="padding-left: 10px;">
                        <div class="radio">
                          <label><input type="radio" name="action" value="delete">Delete</label>
                        </div>

                        <div class="radio">
                            <label><input type="radio" name="action" value="transfer">Transfer to </label> <small>(users with blog permissions)</small>
                            <div style="padding-left: 20px; margin-top: 5px;">
                                <select name="transfer_to" id="assignPostsToUser" class="">
                                  <option value="8">Pick User</option>
                                </select>
                            </div>

                        </div>
                      </div>

                        <div id="delete-user-feedback-info" class="callout callout-info" style="display: none;">
                        </div>

                        <div id="delete-user-error-feedback-info" class="callout callout-danger" style="display: none;">
                        </div>

                      <div class="form-group" id="deletingAnimation" style="display: none;padding: 25px 5px; margin-bottom: 0px;">
                          <div class="cssload-loader deleting-user-animation">Deleting</div>
                      </div>

                  </div>
                </form>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Cancel</button>
                    <button id="deleteUserBtn" type="button" class="btn btn-danger pull-right"><i class="fa fa-eraser" aria-hidden="true"></i> Delete</button>
                </div>
            </div>
          <!-- /.modal-content -->
        </div>
      <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->


