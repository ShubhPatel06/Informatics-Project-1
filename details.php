<?php
// session_start();
include('includes/header.php');
include('config/db_connection.php');
include('customerauthenticate.php');

?>
<div class="hero_area">
  <!-- header section strats -->
  <header class="header_section">
    <div class="container">
      <nav class="navbar navbar-expand-lg custom_nav-container ">
        <a class="navbar-brand nav-link" href="index.php">
          <h3>myPharma</h3>
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
          aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class=""> </span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav">
            <li class="nav-item ">
              <a class="nav-link" href="index.php">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="shop.php">Products</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="uploadprescription.php">Prescription</a>
            </li>
            <?php if (isset($_SESSION['auth_user'])) : ?>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown"
                aria-expanded="false">
                <?= $_SESSION['auth_user']['user_name']; ?>
              </a>
              <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                <li><a class="dropdown-item" href="details.php">My Orders</a></li>
                <li>
                  <form action="logout.php" method="POST">
                    <button type="submit" name="logoutBtn" class="dropdown-item">Logout</button>
                  </form>
                </li>
              </ul>
            </li>
            <?php else: ?>
            <li class="nav-item">
              <a class="nav-link" href="login.php">Login
              </a>
            </li>
            <?php endif; ?>

            <li class="nav-item">
              <a class="nav-link" href="cart.php">
                <i class="fas fa-shopping-cart"></i><span id="item-count"
                  class="position-absolute top-10 start-100 translate-middle badge rounded-pill bg-danger">0
                </span>
              </a>
            </li>
          </ul>
        </div>
      </nav>
    </div>
  </header>
  <div class="container px-4 layout_padding">
    <div class="heading_container heading_center mb-2">
      <h2>
        My Orders
      </h2>
    </div>
    <div class="table-responsive">
      <table id="ordersTable" class="table table-bordered table-striped">
        <thead>
          <tr>
            <th>Order ID</th>
            <th>Order Amount</th>
            <th>Status</th>
            <th>Payment Mode</th>
            <th>Delivery Address</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>

    <div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="viewModalLabel">Order Details</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="container" id="displayDetails">
              <div class="table-responsive">
                <table id="detailsTable" class="table table-bordered table-striped">
                  <thead>
                    <tr>
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

    <div class="modal fade" id="payModal" tabindex="-1" aria-labelledby="payModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <form id="payForm">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="payModalLabel">Make Order Payment</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div id="message"></div>
              <div class="form-group mb-2">
                <label for="">Order ID</label>
                <input readonly type="number" name="order_id" id="order_id" class="form-control order_id"
                  placeholder="Order ID">
              </div>
              <div class="form-group mb-2">
                <label for="">Order Amount</label>
                <input readonly type="number" name="order_amount" id="order_amount" class="form-control order_amount"
                  placeholder="Order Amount">
              </div>
              <div class="form-group mb-2">
                <label for="">Delivery Address</label>
                <textarea name="address" id="address" class="form-control address" rows="2" cols="10"
                  placeholder="Delivery Address"></textarea>
              </div>
              <h6 class="text-center lead">Select Payment Mode</h6>
              <div class="form-group mb-3">
                <select name="paymenttype" class="form-control paymenttype">
                  <option value="" selected disabled>-Select Payment Mode-</option>
                  <option value="1">Debit/Credit Card</option>
                </select>
              </div>
            </div>
            <div class="modal-footer">
              <input type="hidden" name="action" id="action" value="makePayment" />
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary" name="confirmPayment">Pay</button>
            </div>
          </div>
        </form>
      </div>
    </div>

  </div>
</div>


<?php
include('includes/footer.php');
?>
<script>
$(document).ready(function() {
  var orderTable = $('#ordersTable').DataTable({
    "lengthChange": true,
    "processing": true,
    "serverSide": true,
    "searching": false,
    "bInfo": false,
    "order": [],
    "autoWidth": false,
    "ajax": {
      url: "allcode.php",
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


  $("#ordersTable").on('click', '.viewBtn', function() {
    var order_id = $(this).attr("id");
    var action = 'getOrderDetails';
    $.ajax({
      url: 'allcode.php',
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
                            <td>' + value['product_name'] + '</td>\
                            <td>' + value['unit_price'] + '</td>\
                            <td>' + value['quantity'] + '</td>\
                            <td>' + value['total'] + '</td>\
                        </tr>')
        });
      }
    });
  });

  $("#ordersTable").on('click', '.payBtn', function() {
    var order_id = $(this).attr("id");
    var action = 'getOrderbyIDforPayment';
    $.ajax({
      url: 'allcode.php',
      type: "POST",
      data: {
        order_id: order_id,
        action: action
      },
      dataType: 'json',
      success: function(data) {
        $('#payModal').modal('show');
        $('#order_id').val(data.order_id);
        $('#order_amount').val(data.order_amount);
        $('#address').val(data.delivery_address);
      }
    });
  });

  $("form[id='payForm']").validate({
    errorClass: 'error',
    rules: {
      address: "required",
      paymenttype: "required",
    },
    messages: {
      address: "Delivery address is required",
      paymenttype: "Please select payment mode",
    },
    submitHandler: function(form) {
      $.ajax({
        url: 'allcode.php',
        type: 'POST',
        data: $(form).serialize(),
        success: function(response) {
          if (response == "success") {
            $('#message').html(
              '<div class="alert alert-success alert-dismissible fade show" role="alert"><strong>' +
              "Redirecting to payment page" +
              '</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
            );
            $(".alert").delay(1500).slideUp(400, function() {
              $(this).alert('close');
            });
            // document.getElementById("updateuserForm").reset();
            let delay = 3500;
            let url = "https://buy.stripe.com/test_bIYaFv11PdSo9xedQQ";
            setTimeout(function() {
              location = url;
            }, delay);
            orderTable.ajax.reload();

          } else if (response == "Failed") {
            $('#message').html(
              '<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>' +
              "Payment could not be made! Please Try again" +
              '</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
            );
            $(".alert").delay(1500).slideUp(400, function() {
              $(this).alert('close');
            });
            // document.getElementById("updateuserForm").reset();
          } else {
            $('#message').html(
              '<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>' +
              "Payment could not be made! Please Try again" +
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


  get_cart_count();

  function get_cart_count() {
    var action = 'getCartCount';
    $.ajax({
      url: 'allcode.php',
      method: 'post',
      data: {
        'action': action
      },
      success: function(response) {
        $("#item-count").html(response);
      }
    });
  }
});
</script>