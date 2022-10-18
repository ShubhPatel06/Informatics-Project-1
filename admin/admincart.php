<?php
include('../config/db_connection.php');
include('authentication.php');
include('includes/header.php');

?>

<div class="container">
  <div class="row justify-content-center">
    <div class="col-lg-10">

      <div class="table-responsive mt-5">
        <table class="table table-bordered table-striped text-center">
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

        <a href="admincheckout.php"
          class="btn btn-success btn-block text-white lead mb-5 <?= ($grand_total > 1) ? '' : 'disabled'; ?> checkout">Checkout</a>

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

  $(".productQty").on('change', function() {
    var $el = $(this).closest('tr');
    var row_id = $el.find("#rowid").val();
    var product_price = $el.find(".product_price").val();
    var quantity = $el.find(".productQty").val();
    var action = 'changeQty';
    location.reload(true);
    $.ajax({
      url: 'crud.php',
      method: 'post',
      cache: false,
      data: {
        'quantity': quantity,
        'row_id': row_id,
        'product_price': product_price,
        'action': action
      },
      success: function(response) {
        // window.location.href = '';
        // $('#cartItems').load(location.href + " #cartItems");
        // $('#cartItems').load(" #cartItems");
        // alert("Quantity was changed");
        $(window).on('load', function() {
          $('#cartModal').modal('show');
        });
      }
    });
  });

  $(document).on('click', '.removeItem', function() {
    var id = $(this).data('row-id');
    var action = 'removeItem';
    if (confirm('Do you want to remove the product from your cart?')) {
      $.ajax({
        type: 'POST',
        url: 'crud.php',
        data: {
          'id': id,
          'action': action
        },
        success: function(result) {

          window.location.href = '';
        }
      });
    } else {
      alert('Item was not removed');
    }
  });

  $(document).on('click', '.clearCart', function() {
    var action = 'clearCart';

    if (confirm('Do you want to clear the cart?')) {
      $.ajax({
        type: 'POST',
        url: 'crud.php',
        data: {
          'action': action
        },
        success: function(response) {
          window.location.href = '';
        }
      });
    } else {
      alert('Cart was not cleared');
    }
  });

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