<!--Update Banner Modal -->
<div class="modal fade text-left" id="update_sub_admin_modal" tabindex="-1" role="dialog"
  aria-hidden="true">
  <div class="modal-dialog modal-dialog-top modal-dialog-scrollable modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Update Sub Admin</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="resetUpdateSubAdminForm()">
          <i class="bx bx-x"></i>
        </button>
      </div>
      <div class="modal-body">
        <form class="form form-vertical" id="update_sub_admin_data" enctype="multipart/form-data">
          @csrf
          <input type="hidden" name="sub_admin_id" id="sub_admin_id">
          <div class="form-body">
            <div class="row">
              <!-- <div class="col-12">
                <div class="form-group">
                  <label for="update_sub_admin_profile_pic">Profile Picture</label>
                  <div class="position-relative has-icon-left">
                    <input type="file" class="form-control-file" id="update_sub_admin_profile_pic" name="profile_pic">
                  </div>
                </div>
              </div> -->
              <div class="col-12">
                <div class="form-group">
                  <label for="update_sub_admin_name">Name</label>
                  <div class="position-relative has-icon-left">
                    <input type="text" id="update_sub_admin_name" class="form-control" name="name" placeholder="Enter Name">
                    <div class="form-control-position">
                      <i class='bx bxs-rename'></i>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-12">
                <div class="form-group">
                  <label for="update_game_code">Phone</label>
                  <div class="position-relative has-icon-left">
                    <input type="number" id="update_sub_admin_phone" class="form-control" name="phone" placeholder="Enter Phone Number">
                    <div class="form-control-position">
                      <i class='bx bxs-label' ></i>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-12">
                <div class="form-group">
                  <label for="update_game_fraction">Email</label>
                  <div class="position-relative has-icon-left">
                    <input type="email" id="update_sub_admin_email" class="form-control" name="email" placeholder="Enter Email">
                    <div class="form-control-position">
                      <i class='bx bxs-label' ></i>
                    </div>
                  </div>
                </div>
              </div>
              <p class="text-danger">Note:- Keep Blank the <strong>Password</strong> field, If you don't want to update the Password.</p>
              <hr>
              <div class="col-6">
                <div class="form-group">
                  <label for="update_game_type_video_link">Password</label>
                  <div class="position-relative has-icon-left">
                    <input type="password" id="update_sub_admin_password" class="form-control" name="password" placeholder="Enter Password">
                    <div class="form-control-position">
                      <i class='bx bx-link' ></i>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label for="update_sub_admin_confirm_password">Confirm Password</label>
                  <div class="position-relative has-icon-left">
                    <input type="password" id="update_sub_admin_confirm_password" class="form-control" name="confirm_password" placeholder="Enter Password">
                    <div class="form-control-position">
                      <i class='bx bx-link' ></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light-secondary" data-dismiss="modal" onclick="resetUpdateSubAdminForm()">
          <i class="bx bx-x d-block d-sm-none"></i>
          <span class="d-none d-sm-block">Close</span>
        </button>
        <button type="button" class="btn btn-primary ml-1 update-sub-admin-btn">
          <i class="bx bx-check d-block d-sm-none"></i>
         Update
        </button>
      </div>
    </div>
  </div>
</div>
<script>

$(document).on('click', '.sub-admin-edit-btn', function() {

    var data = $(this).attr('data-value').split('|');
    $('#sub_admin_id').val(data[0]);
    $('#update_sub_admin_name').val(data[1]);
    $('#update_sub_admin_email').val(data[2]);
    $('#update_sub_admin_phone').val(data[3]);
    $('#update_sub_admin_modal').modal('toggle');

    setTimeout(() => {
        $('#update_sub_admin_password').val('');
        $('#update_sub_admin_confirm_password').val('');
      },100);
});

$(document).on('click', '.update-sub-admin-btn', function() {

    //get and set the from data.
    var myform = document.getElementById("update_sub_admin_data");
    var formData = new FormData(myform);
    formData.append('_token', '{{ csrf_token() }}');

    $('#update_sub_admin_data :file').each(function() {
        var thisName = $(this).attr("name");
        var image = $('input[name='+thisName+']');
        if (typeof image[0] !== 'undefined') {
          var FileToUpload = image[0].files[0];
          formData.append(thisName, FileToUpload);
        }
        formData.append(thisName, null);
    });
    
    $(this).html('Updating...').attr('disabled', true);
    $.ajax({
        type: "post",
        data: formData,
        cache:false,
        contentType: false,
        processData: false,
        url: "./sub-admins/update",
        success: function(result) {
            $('.update-sub-admin-btn').html('Update').attr('disabled', false);
            if (result.success == false) {
                Toast.fire({
                  icon: 'error',
                  title: result.message
                })
            }else{
                Toast.fire({
                  icon: 'success',
                  title: result.message
                })
                resetUpdateSubAdminForm()
                $('#update_sub_admin_modal').modal('toggle');
                $('#SubAdminTable').DataTable().ajax.reload();
            }
        }
    });

    function resetUpdateSubAdminForm() {
      $('#update_sub_admin_data').trigger("reset");
    }
});
</script>