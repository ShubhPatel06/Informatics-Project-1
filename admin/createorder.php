<?php
include('../config/db_connection.php');
include('authentication.php');
include('includes/header.php');

?>

<div class="container-fluid px-4">
  <h1 class="mt-4">Create Order</h1>
  <ol class="breadcrumb mb-4">
    <li class="breadcrumb-item active">Dashboard</li>
    <li class="breadcrumb-item">Create Order</li>
  </ol>
  <div id="message"></div>
  <div align="right" class="mb-2 position-relative">
    <!-- <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#cartModal">
      <i class="fas fa-shopping-cart"></i><span id="item-count"
        class="position-absolute top-10 start-100 translate-middle badge rounded-pill bg-danger">0
      </span>
    </button> -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" href="admincart.php">
          <i class="fas fa-shopping-cart"></i><span id="item-count"
            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">0
          </span>
        </a>
      </li>
    </ul>
  </div>
  <div class="table-responsive">
    <table id="createTable" class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>Product ID</th>
          <th>Product Name</th>
          <th>Product Description</th>
          <th>Product Image</th>
          <th>Unit Price</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
      </tbody>
    </table>
  </div>

  <div class="modal fade" id="cartModal" tabindex="-1" aria-labelledby="cartModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="cartModalLabel">Cart</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row justify-content-center">

            <div class="col-lg-10">

              <div id="cartLoad" class="table-responsive mt-3">
                <table id="cartItems" class="table table-bordered table-striped text-center">
                  <thead>
                    <tr>
                      <td colspan="6">
                        <h4 class="text-center text-info m-0">Cart Items</h4>
                      </td>
                    </tr>
                    <tr>
                      <th></th>
                      <th>Product Name</th>
                      <th>Quantity</th>
                      <th>Unit Price</th>
                      <th>Sub-Total</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
$getCartItems = "SELECT * FROM `tbl_cart`";
$getCartItems_execute = mysqli_query($connection, $getCartItems);
$grand_total = 0;
while ($result = mysqli_fetch_assoc($getCartItems_execute)):
      ?>
                    <tr class="mt-3">
                      <td><input type="hidden" name="row_id" value="<?= $result['id'];?>" id="rowid"></td>
                      <td><?= $result['product_name'];?></td>
                      <td><input type="number" class="form-control productQty" value="<?= $result['quantity'] ?>"
                          style="width:75px;">
                      </td>
                      <td><?= number_format($result['product_price'],2); ?></td>
                      <input type="hidden" class="product_price" value="<?= $result['product_price'] ?>">
                      <td><?= number_format($result['total_price'], 2); ?></td>
                      <td><a href="#" class="btn btn-sm btn-danger removeItem" data-row-id="<?= $result['id'];?>"><i
                            class="fas fa-trash"></i></a></td>

                    </tr>
                    <?php $grand_total += $result['total_price']; ?>
                    <?php endwhile; ?>
                    <div class="">
                      <tr>
                        <td colspan="3"></td>
                        <td class="right"><strong>Total</strong></td>
                        <td><?= number_format($grand_total,2); ?></td>
                      </tr>
                    </div>
                  </tbody>
                </table>

                <a class="btn btn-danger btn-block text-white lead mb-5 clearCart">Clear Cart</a>

                <a href="checkout.php"
                  class="btn btn-success btn-block text-white lead mb-5 <?= ($grand_total > 1) ? '' : 'disabled'; ?> checkout">Checkout</a>

              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
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
    var createTable = $('#createTable').DataTable({
      "lengthChange": true,
      "processing": true,
      "serverSide": true,
      "order": [],
      "autoWidth": false,
      "ajax": {
        url: "crud.php",
        type: "POST",
        data: {
          action: 'getProductsforOrder'
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

    $("#productsTable").on('click', '.updateBtn', function() {
      var product_id = $(this).attr("id");
      var action = 'getProductbyID';

      $.ajax({
        url: 'crud.php',
        type: "POST",
        data: {
          product_id: product_id,
          action: action
        },
        dataType: "json",
        success: function(data) {
          $('#updateproductModal').modal('show');
          $('#updateproduct_id').val(data.product_id);
          $('#updateproduct_name').val(data.product_name);
          $('#updateproduct_description').val(data.product_description);
          $('#updateunit_price').val(data.unit_price);
          $('#updateavailable_quantity').val(data.available_quantity);
          $('#updatesubcategory_id').val(data.subcategory_id);
          $('#updatemedType_id').val(data.medicine_type);
          $('#updateadded_by').val(data.added_by);
        }
      });
    });

    $(document).on('click', '.addtocart', function() {
      var product_id = $(this).data('product-id');
      var product_name = $(this).data('product-name');
      var unit_price = $(this).data('unit-price');
      var quantity = $('#' + product_id).val();
      var action = 'addtoCart';

      $.ajax({
        url: "crud.php",
        type: "POST",
        data: {
          'product_id': product_id,
          'product_name': product_name,
          'unit_price': unit_price,
          'quantity': quantity,
          'action': action
        },

        success: function(response) {
          if (response == "cartFail") {
            $('#message').html(
              '<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>' +
              "Product already exists in cart" +
              '</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
            );
            get_cart_count();
            $(".alert").delay(1500).slideUp(400, function() {
              $(this).alert('close');
            });
          } else if (response == "success") {
            $('#message').html(
              '<div class="alert alert-success alert-dismissible fade show" role="alert"><strong>' +
              "Product added to cart" +
              '</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
            );
            get_cart_count();
            $(".alert").delay(1500).slideUp(400, function() {
              $(this).alert('close');
            });
            // window.location.href = '';

          } else if (response == "failed") {
            $('#message').html(
              '<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>' +
              "Product could not be added to cart" +
              '</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
            );
            get_cart_count();
            $(".alert").delay(1500).slideUp(400, function() {
              $(this).alert('close');
            });
          } else {
            $('#message').html(
              '<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>' +
              "Product could not be added to cart" +
              '</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
            );
            get_cart_count();
            $(".alert").delay(1500).slideUp(400, function() {
              $(this).alert('close');
            });
          }
        }
      });
    });

    // $(".productQty").on('change', function() {
    //   var $el = $(this).closest('tr');
    //   var row_id = $el.find("#rowid").val();
    //   var product_price = $el.find(".product_price").val();
    //   var quantity = $el.find(".productQty").val();
    //   var action = 'changeQty';
    //   location.reload(true);
    //   $.ajax({
    //     url: 'crud.php',
    //     method: 'post',
    //     cache: false,
    //     data: {
    //       'quantity': quantity,
    //       'row_id': row_id,
    //       'product_price': product_price,
    //       'action': action
    //     },
    //     success: function(response) {
    //       // window.location.href = '';
    //       // $('#cartItems').load(location.href + " #cartItems");
    //       // $('#cartItems').load(" #cartItems");
    //       // alert("Quantity was changed");
    //       $(window).on('load', function() {
    //         $('#cartModal').modal('show');
    //       });
    //     }
    //   });
    // });

    // $(document).on('click', '.removeItem', function() {
    //   var id = $(this).data('row-id');
    //   var action = 'removeItem';

    //   $.ajax({
    //     type: 'POST',
    //     url: 'crud.php',
    //     data: {
    //       'id': id,
    //       'action': action
    //     },
    //     success: function(result) {
    //       confirm('Do you want to remove the product from your cart?');
    //       window.location.href = '';
    //     }
    //   });
    // });

    get_cart_count();

    function get_cart_count() {
      var action = 'getCartCount';
      $.ajax({
        url: 'crud.php',
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