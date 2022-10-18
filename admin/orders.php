<?php
include('../config/db_connection.php');
include('authentication.php');
include('includes/header.php');

?>

<div class="container-fluid px-4">
  <h1 class="mt-4">Orders</h1>
  <ol class="breadcrumb mb-4">
    <li class="breadcrumb-item active">Dashboard</li>
    <li class="breadcrumb-item">Orders</li>
  </ol>
  <div align="right" class="mb-2">
    <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#userModal">
      Add Order
    </button>
  </div>
  <div class="table-responsive">
    <table id="ordersTable" class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>ID</th>
          <th>Customer ID</th>
          <th>Order Amount</th>
          <th>Status</th>
          <th>Created at</th>
          <th>Payment Mode</th>
          <th>Delivery Address</th>
          <th>Updated at</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
      </tbody>
    </table>
  </div>

  <!-- Add User Modal -->
  <div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
    <div class="modal-dialog ">
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
  <div class="modal fade" id="updateorderModal" tabindex="-1" aria-labelledby="updateorderModalLabel"
    aria-hidden="true">
    <div class="modal-dialog ">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="updateorderModalLabel"><i class='fa-solid fa-pen-to-square'></i> Edit Order</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="updateorderForm">
          <div class="modal-body">
            <div id="message"></div>

            <div class="form-group mb-3">
              <label for="">Order ID</label>
              <input readonly type="number" id="updateorder_id" name="updateorder_id" class="form-control"
                placeholder="Order ID">
            </div>
            <div class="form-group mb-3">
              <label for="">Customer ID</label>
              <input readonly type="number" id="updatecustomer_id" name="updatecustomer_id" class="form-control"
                placeholder="Customer ID">
            </div>
            <div class="form-group mb-3">
              <label for="">Order Amount</label>
              <input readonly type="number" id="updateorder_amount" name="updateorder_amount" class="form-control"
                placeholder="Last Name">
            </div>
            <div class="form-group mb-3">
              <label for="">Order Status</label>
              <input type="text" id="updateorder_status" name="updateorder_status" class="form-control"
                placeholder="Order Status">
            </div>
            <div class="form-group mb-3">
              <label for="">Payment Method</label>
              <input readonly type="number" id="updatepaymenttype" name="updatepaymenttype" class="form-control"
                placeholder="Payment Type">
            </div>
            <div class="form-group mb-3">
              <label for="">Delivery Address</label>
              <textarea name="updateaddress" id="updateaddress" class="form-control" rows="2" cols="10"
                placeholder="Enter Delivery Address"></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <input type="hidden" name="action" id="action" value="editOrder" />
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" name="updateOrder">Update</button>
          </div>
        </form>

      </div>
    </div>
  </div>

  <div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="viewModalLabel">View Order Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="container" id="displayDetails">
            <div class="table-responsive">
              <table id="detailsTable" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>Product ID</th>
                    <th>Product Name</th>
                    <th>Unit Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                  </tr>
                </thead>
                <tbody class="detailsData">
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
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
  var orderTable = $('#ordersTable').DataTable({
    "lengthChange": true,
    "processing": true,
    "serverSide": true,
    "order": [],
    "autoWidth": false,
    "ajax": {
      url: "crud.php",
      type: "POST",
      data: {
        action: 'getOrders'
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

  $("#ordersTable").on('click', '.updateBtn', function() {
    var order_id = $(this).attr("id");
    var action = 'getOrderbyID';

    $.ajax({
      url: 'crud.php',
      type: "POST",
      data: {
        order_id: order_id,
        action: action
      },
      dataType: "json",
      success: function(data) {
        $('#updateorderModal').modal('show');
        $('#updateorder_id').val(data.order_id);
        $('#updatecustomer_id').val(data.customer_id);
        $('#updateorder_amount').val(data.order_amount);
        $('#updateorder_status').val(data.order_status);
        $('#updatepaymenttype').val(data.payment_type);
        $('#updateaddress').val(data.delivery_address);
      }
    });
  });

  $("form[id='updateorderForm']").validate({
    errorClass: 'error',
    rules: {
      updateorder_status: "required",
      updateaddress: "required",
    },
    messages: {
      updateorder_status: "Order Status is required",
      updateaddress: "Delivery address is required",
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
              "Order updated successfully" +
              '</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
            );
            $(".alert").delay(1500).slideUp(400, function() {
              $(this).alert('close');
            });
            // document.getElementById("updateuserForm").reset();
            orderTable.ajax.reload();

          } else if (response == "Failed") {
            $('#message').html(
              '<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>' +
              "Order could not be updated! Please Try again" +
              '</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
            );
            $(".alert").delay(1500).slideUp(400, function() {
              $(this).alert('close');
            });
            // document.getElementById("updateuserForm").reset();
          } else {
            $('#message').html(
              '<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>' +
              "Order could not be updated! Please Try again" +
              '</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
            );
            $(".alert").delay(1500).slideUp(400, function() {
              $(this).alert('close');
            });
            // document.getElementById("updateuserForm").reset();
          }
        }
      });
    }
  });

  $("#ordersTable").on('click', '.viewBtn', function() {
    var order_id = $(this).attr("id");
    var action = 'getOrderDetails';
    $.ajax({
      url: 'crud.php',
      type: "POST",
      data: {
        order_id: order_id,
        action: action
      },
      dataType: 'json',
      success: function(data) {
        $('#viewModal').modal('show');
        $('.detailsData').html('');
        $.each(data, function(key, value) {
          $('.detailsData').append('<tr>\
                            <td>' + value['product_id'] + '</td>\
                            <td>' + value['product_name'] + '</td>\
                            <td>' + value['unit_price'] + '</td>\
                            <td>' + value['quantity'] + '</td>\
                            <td>' + value['total'] + '</td>\
                        </tr>')
        });
      }
    });

  });
});
</script>