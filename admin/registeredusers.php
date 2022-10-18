<?php
include('../config/db_connection.php');
include('authentication.php');
include('includes/header.php');

?>

<div class="container-fluid px-4">
  <h1 class="mt-4">Users</h1>
  <ol class="breadcrumb mb-4">
    <li class="breadcrumb-item active">Dashboard</li>
    <li class="breadcrumb-item">Users</li>
  </ol>
  <div align="right" class="mb-2">
    <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#userModal">
      Add User
    </button>
  </div>
  <div class="table-responsive">
    <table id="usersTable" class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>ID</th>
          <th>First Name</th>
          <th>Last Name</th>
          <th>PhoneNo</th>
          <th>Email</th>
          <th>Gender</th>
          <th>Date of Birth</th>
          <th>Role</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
      </tbody>
    </table>
  </div>

  <!-- Add User Modal -->
  <div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="userModalLabel"><i class='fa fa-plus'></i> Add User</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="registerForm">
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
              <input type="date" id="dob" name="dob" class="form-control" placeholder="Date of Birth">
            </div>
            <div class="form-group mb-3">
              <input type="number" id="role" name="role" class="form-control" placeholder="Role ID">
            </div>
          </div>
          <div class="modal-footer">
            <input type="hidden" name="action" id="action" value="addUser" />

            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" name="saveUser">Save</button>
          </div>
        </form>

      </div>
    </div>
  </div>

  <!-- Update User Modal -->
  <div class="modal fade" id="updateuserModal" tabindex="-1" aria-labelledby="updateuserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="updateuserModalLabel"><i class='fa-solid fa-pen-to-square'></i> Edit User</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="updateuserForm">
          <div class="modal-body">
            <div id="message"></div>

            <div class="form-group mb-3">
              <label for="">User ID</label>
              <input readonly type="number" id="user_id" name="user_id" class="form-control" placeholder="User ID">
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
              <label for="">Email</label>
              <input type="email" id="updateemail" name="updateemail" class="form-control" placeholder="Email">
            </div>
            <div class="form-group mb-3">
              <label for="">Gender</label>
              <input type="text" id="updategender" name="updategender" class="form-control" placeholder="Gender">
            </div>
            <div class="form-group mb-3">
              <label for="">Date of Birth</label>
              <input type="date" id="updatedob" name="updatedob" class="form-control" placeholder="Date of Birth">
            </div>
            <div class="form-group mb-3">
              <label for="">Role</label>
              <input type="number" id="updaterole" name="updaterole" class="form-control" placeholder="Role ID">
            </div>
          </div>
          <div class="modal-footer">
            <input type="hidden" name="action" id="action" value="editUser" />
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" name="updateUser">Update</button>
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
  var userTable = $('#usersTable').DataTable({
    "lengthChange": true,
    "processing": true,
    "serverSide": true,
    "order": [],
    "autoWidth": false,
    "ajax": {
      url: "crud.php",
      type: "POST",
      data: {
        action: 'getUsers'
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

  $("form[id='registerForm']").validate({
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
      dob: "required",
      role: "required",
    },
    messages: {
      first_name: "First name is required",
      last_name: "Last name is required",
      phoneNo: "Phone number is required",
      email: "Enter a valid email",
      password: "Password is required",
      dob: "Date of Birth is required",
      role: "Role ID is required",
    },
    submitHandler: function(form) {
      $.ajax({
        url: 'crud.php',
        type: 'POST',
        data: $(form).serialize(),
        success: function(response) {
          if (response == "success") {
            alert("Registered Successfully!");
            document.getElementById("registerForm").reset();
            userTable.ajax.reload();
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
            document.getElementById("registerForm").reset();
          } else if (response == "Failed2") {
            $('#error').html(
              '<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>' +
              "Registration Failed! Please Try again" +
              '</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
            );
            $(".alert").delay(1500).slideUp(400, function() {
              $(this).alert('close');
            });
            document.getElementById("registerForm").reset();
          } else if (response == "emailFail") {
            $('#error').html(
              '<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>' +
              "Email Already Exists!" +
              '</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
            );
            $(".alert").delay(1500).slideUp(400, function() {
              $(this).alert('close');
            });
            document.getElementById("registerForm").reset();
          } else {
            $('#error').html(
              '<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>' +
              "Registration Failed! Please Try again" +
              '</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
            );
            $(".alert").delay(1500).slideUp(400, function() {
              $(this).alert('close');
            });
            document.getElementById("registerForm").reset();
          }
        }
      });
    }
  });

  $("#usersTable").on('click', '.updateBtn', function() {
    var user_id = $(this).attr("id");
    var action = 'getUserbyID';

    $.ajax({
      url: 'crud.php',
      type: "POST",
      data: {
        user_id: user_id,
        action: action
      },
      dataType: "json",
      success: function(data) {
        $('#updateuserModal').modal('show');
        $('#user_id').val(data.user_id);
        $('#updatefirst_name').val(data.first_name);
        $('#updatelast_name').val(data.last_name);
        $('#updatephoneNo').val(data.phoneNo);
        $('#updateemail').val(data.email);
        $('#updategender').val(data.gender);
        $('#updatedob').val(data.dob);
        $('#updaterole').val(data.role);
      }
    });
  });

  $("form[id='updateuserForm']").validate({
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
      dob: "required",
      role: "required",
    },
    messages: {
      first_name: "First name is required",
      last_name: "Last name is required",
      phoneNo: "Phone number is required",
      email: "Enter a valid email",
      password: "Password is required",
      cpassword: "Date of Birth is required",
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
              "User update successfully" +
              '</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
            );
            $(".alert").delay(1500).slideUp(400, function() {
              $(this).alert('close');
            });
            // document.getElementById("updateuserForm").reset();
            userTable.ajax.reload();

          } else if (response == "failed") {
            $('#message').html(
              '<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>' +
              "User could not be update! Please Try again" +
              '</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
            );
            $(".alert").delay(1500).slideUp(400, function() {
              $(this).alert('close');
            });
            document.getElementById("updateuserForm").reset();
          } else {
            $('#message').html(
              '<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>' +
              "User could not be update! Please Try again" +
              '</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
            );
            $(".alert").delay(1500).slideUp(400, function() {
              $(this).alert('close');
            });
            document.getElementById("updateuserForm").reset();
          }
        }
      });
    }
  });

  $("#usersTable").on('click', '.deleteBtn', function() {
    var user_id = $(this).attr("id");
    var action = 'deleteUser';
    if (confirm("Are you sure you want to delete this user?")) {
      $.ajax({
        url: 'crud.php',
        type: "POST",
        data: {
          user_id: user_id,
          action: action
        },
        success: function(response) {
          userTable.ajax.reload();
        }
      });
    } else {
      return false;
    }
  });

});
</script>