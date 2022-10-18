<?php
session_start();
include('includes/header.php');
include('config/db_connection.php');

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
              <a class="nav-link" href="">
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
  <!-- end header section -->

  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-10">

        <div class="table-responsive mt-2">
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

          <a href="checkout.php"
            class="btn btn-success btn-block text-white lead mb-5 <?= ($grand_total > 1) ? '' : 'disabled'; ?> checkout">Checkout</a>

        </div>
      </div>
    </div>
  </div>


</div>

<?php
include('includes/footer.php');
?>
<script>
$(document).ready(function() {

  $(".productQty").on('change', function() {
    var $el = $(this).closest('tr');

    var row_id = $el.find("#rowid").val();
    var product_price = $el.find(".product_price").val();
    var quantity = $el.find(".productQty").val();
    var action = 'changeQty';
    // location.reload(true);
    $.ajax({
      url: 'allcode.php',
      method: 'post',
      cache: false,
      data: {
        'quantity': quantity,
        'row_id': row_id,
        'product_price': product_price,
        'action': action
      },
      success: function(response) {
        window.location.href = '';

      }
    });
  });

  $(document).on('click', '.removeItem', function() {
    var id = $(this).data('row-id');
    var action = 'removeItem';
    if (confirm('Do you want to remove the product from your cart?')) {
      $.ajax({
        type: 'POST',
        url: 'allcode.php',
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
        url: 'allcode.php',
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