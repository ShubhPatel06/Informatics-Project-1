<?php
include('../config/db_connection.php');
include('authentication.php');
include('includes/header.php');

?>

<div class="container-fluid px-4">
  <h1 class="mt-4">Hospital Staff</h1>
  <ol class="breadcrumb mb-4">
    <li class="breadcrumb-item active">Dashboard</li>
    <li class="breadcrumb-item">Hospital Staff</li>
  </ol>
  <div align="right" class="mb-2">
    <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#staffModal">
      Add Staff
    </button>
  </div>
  <div class="table-responsive">
    <table id="staffTable" class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>ID</th>
          <th>First Name</th>
          <th>Last Name</th>
          <th>PhoneNo</th>
          <th>Hospital Email</th>
          <th>Gender</th>
          <th>Role</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
      </tbody>
    </table>
  </div>

  <!-- Add User Modal -->
  <div class="modal fade" id="staffModal" tabindex="-1" aria-labelledby="staffModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="staffModalLabel"><i class='fa fa-plus'></i> Add Hospital Staff</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="staffForm">
          <div class="modal-body">
            <div id="error"></div>
            <div class="form-group mb-3">
              <input type="text" id="first_name" name="first_name" class="form-control" placeholder="First Name">
            </div>
            <div class="form-group mb-3">
              <input type="text" id="last_name" name="last_name" class="form-control" placeholder="Last Name">
            </div>
            <div class="form-group mb-3">
              <input type="tel" id="phoneNo" name="phoneNo" class="form-control" placeholder="Phone Number">
            </div>
            <div class="form-group mb-3">
              <input type="email" id="email" name="email" class="form-control" placeholder="Email">
            </div>
            <select name="gender" class="form-select mb-3" aria-label="Default select example">
              <option selected>Gender</option>
              <option value="Male">Male</option>
              <option value="Female">Female</option>
            </select>
            <div class="form-group mb-3">
              <input type="password" id="password" name="password" class="form-control" placeholder="Password">
            </div>
            <div class="form-group mb-3">
              <input type="number" id="role" name="role" class="form-control" placeholder="Role ID">
            </div>
          </div>
          <div class="modal-footer">
            <input type="hidden" name="action" id="action" value="addStaff" />
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" name="saveStaff">Save</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Update User Modal -->
  <div class="modal fade" id="updatestaffModal" tabindex="-1" aria-labelledby="updatestaffModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="updatestaffModalLabel"><i class='fa-solid fa-pen-to-square'></i> Edit Staff</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="updatestaffForm">
          <div class="modal-body">
            <div id="message"></div>

            <div class="form-group mb-3">
              <label for="">Staff ID</label>
              <input readonly type="number" id="staff_id" name="staff_id" class="form-control" placeholder="Staff ID">
            </div>
            <div class="form-group mb-3">
              <label for="">First Name</label>
              <input type="text" id="updatefirst_name" name="updatefirst_name" class="form-control"
                placeholder="First Name">
            </div>
            <div class="form-group mb-3">
              <label for="">Last Name</label>
              <input type="text" id="updatelast_name" name="updatelast_name" class="form-control"
                placeholder="Last Name">
            </div>
            <div class="form-group mb-3">
              <label for="">Phone Number</label>
              <input type="tel" id="updatephoneNo" name="updatephoneNo" class="form-control" placeholder="Phone Number">
            </div>
            <div class="form-group mb-3">
              <label for="">Hospital Email</label>
              <input type="email" id="updateemail" name="updateemail" class="form-control" placeholder="Hospital Email">
            </div>
            <div class="form-group mb-3">
              <label for="">Gender</label>
              <input type="text" id="updategender" name="updategender" class="form-control" placeholder="Gender">
            </div>
            <div class="form-group mb-3">
              <label for="">Role</label>
              <input readonly type="number" id="updaterole" name="updaterole" class="form-control"
                placeholder="Role ID">
            </div>
          </div>
          <div class="modal-footer">
            <input type="hidden" name="action" id="action" value="editStaff" />
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" name="updateStaff">Update</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<?php
include('includes/footer.php');
include('includes/scripts.php');
?>

<script>
$(document).ready(function() {
  var staffTable = $('#staffTable').DataTable({
    "lengthChange": true,
    "processing": true,
    "serverSide": true,
    "order": [],
    "autoWidth": false,
    "ajax": {
      url: "crud.php",
      type: "POST",
      data: {
        action: 'getStaff'
      },
      dataType: "json"
    },
    "columnDefs": [{
      "targets": [0],
      "orderable": false,
    }, ],
    "fixedColumns": false,
    "pageLength": 10
  });

  $("form[id='staffForm']").validate({
    errorClass: 'error',
    rules: {
      first_name: "required",
      last_name: "required",
      phoneNo: "required",
      email: {
        required: true,
        email: true,
        maxlength: 100,
      },
      password: "required",
      role: "required",
    },
    messages: {
      first_name: "First name is required",
      last_name: "Last name is required",
      phoneNo: "Phone number is required",
      email: "Enter a valid hospital email",
      password: "Password is required",
      role: "Role ID is required",
    },
    submitHandler: function(form) {
      $.ajax({
        url: 'crud.php',
        type: 'POST',
        data: $(form).serialize(),
        success: function(response) {
          if (response == "success") {
            $('#error').html(
              '<div class="alert alert-success alert-dismissible fade show" role="alert"><strong>' +
              "Hospital Staff registered successfully!" +
              '</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
            );
            document.getElementById("staffForm").reset();
            staffTable.ajax.reload();
            $(".alert").delay(1500).slideUp(400, function() {
              $(this).alert('close');
            });
          } else if (response == "Failed") {
            $('#error').html(
              '<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>' +
              "Registration Failed! Please Try again" +
              '</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
            );
            $(".alert").delay(1500).slideUp(400, function() {
              $(this).alert('close');
            });
            document.getElementById("staffForm").reset();
          } else if (response == "emailFail") {
            $('#error').html(
              '<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>' +
              "An email of this hospital already exists!" +
              '</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
            );
            $(".alert").delay(1500).slideUp(400, function() {
              $(this).alert('close');
            });
            document.getElementById("staffForm").reset();
          } else {
            $('#error').html(
              '<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>' +
              "Registration Failed! Please Try again" +
              '</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
            );
            $(".alert").delay(1500).slideUp(400, function() {
              $(this).alert('close');
            });
            document.getElementById("staffForm").reset();
          }
        }
      });
    }
  });

  $("#staffTable").on('click', '.updateBtn', function() {
    var staff_id = $(this).attr("id");
    var action = 'getStaffbyID';

    $.ajax({
      url: 'crud.php',
      type: "POST",
      data: {
        staff_id: staff_id,
        action: action
      },
      dataType: "json",
      success: function(data) {
        $('#updatestaffModal').modal('show');
        $('#staff_id').val(data.staff_id);
        $('#updatefirst_name').val(data.first_name);
        $('#updatelast_name').val(data.last_name);
        $('#updatephoneNo').val(data.phoneNo);
        $('#updateemail').val(data.hospital_email);
        $('#updategender').val(data.gender);
        $('#updaterole').val(data.role);
      }
    });
  });

  $("form[id='updatestaffForm']").validate({
    errorClass: 'error',
    rules: {
      first_name: "required",
      last_name: "required",
      phoneNo: "required",
      email: {
        required: true,
        email: true,
        maxlength: 100,
      },
      password: "required",
      role: "required",
    },
    messages: {
      first_name: "First name is required",
      last_name: "Last name is required",
      phoneNo: "Phone number is required",
      email: "Enter a valid email",
      password: "Password is required",
      role: "Role ID is required",
    },
    submitHandler: function(form) {
      $.ajax({
        url: 'crud.php',
        type: 'POST',
        data: $(form).serialize(),
        success: function(response) {
          if (response == "success") {
            $('#message').html(
              '<div class="alert alert-success alert-dismissible fade show" role="alert"><strong>' +
              "Staff updated successfully" +
              '</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
            );
            $(".alert").delay(1500).slideUp(400, function() {
              $(this).alert('close');
            });
            staffTable.ajax.reload();

          } else if (response == "failed") {
            $('#message').html(
              '<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>' +
              "Staff could not be updated! Please Try again" +
              '</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
            );
            $(".alert").delay(1500).slideUp(400, function() {
              $(this).alert('close');
            });
            document.getElementById("updatestaffForm").reset();
          } else {
            $('#message').html(
              '<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>' +
              "Staff could not be update! Please Try again" +
              '</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
            );
            $(".alert").delay(1500).slideUp(400, function() {
              $(this).alert('close');
            });
            document.getElementById("updatestaffForm").reset();
          }
        }
      });
    }
  });

  $("#staffTable").on('click', '.deleteBtn', function() {
    var staff_id = $(this).attr("id");
    var action = 'deleteStaff';
    if (confirm("Are you sure you want to delete this staff?")) {
      $.ajax({
        url: 'crud.php',
        type: "POST",
        data: {
          staff_id: staff_id,
          action: action
        },
        success: function(response) {
          staffTable.ajax.reload();
        }
      });
    } else {
      return false;
    }
  });
});
</script>