<?php
// session_start();
include('includes/header.php');
include('config/db_connection.php');
include('customerauthenticate.php');


$grand_total = 0;
$allItems = '';
$items = [];

$sql = "SELECT CONCAT(product_name, '(',quantity,')') AS ItemQty, total_price FROM `tbl_cart`";
$query_execute = mysqli_query($connection, $sql);

while ($result = mysqli_fetch_assoc($query_execute)) {
  $grand_total += $result['total_price'];
  $items[] = $result['ItemQty'];
}
$allItems = implode(', ', $items);
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
  <!-- end header section -->

  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-6 px-4 pb-4" id="orderInfo">
        <!-- <h4 class="text-center text-info p-2">Complete your order!</h4> -->
        <div class="jumbotron p-3 mb-2 text-center bg-light">
          <h6 class="lead"><b>Products : </b><?= $allItems; ?></h6>
          <h5><b>Amount to Pay : </b><?= number_format($grand_total,2) ?>/-</h5>
        </div>
        <form id="orderForm">
          <input type="hidden" name="products" value="<?= $allItems; ?>">
          <input type="hidden" name="grand_total" value="<?= $grand_total; ?>">
          <input type="hidden" name="customer_id" value="<?= $_SESSION['auth_user']['user_id']; ?>">

          <div class="form-group mb-2">
            <input readonly type="text" name="customer_name" value="<?= $_SESSION['auth_user']['user_name']; ?>"
              class="form-control customer_name" placeholder="Name">
          </div>
          <div class="form-group mb-2">
            <input readonly type="email" name="email" value="<?= $_SESSION['auth_user']['email']; ?>"
              class="form-control email" placeholder="E-Mail">
          </div>
          <div class="form-group mb-2">
            <input readonly type="tel" name="phoneNo" value="<?= $_SESSION['auth_user']['phoneNo']; ?>"
              class="form-control phoneNo" placeholder="Phone Number">
          </div>
          <div class="form-group mb-2">
            <textarea name="address" class="form-control" rows="2" cols="10"
              placeholder="Enter Delivery Address Here..."></textarea>
          </div>
          <h6 class="text-center lead">Select Payment Mode</h6>
          <div class="form-group mb-3">
            <select name="paymenttype" class="form-control">
              <option value="" selected disabled>-Select Payment Mode-</option>
              <option value="1">Debit/Credit Card</option>
            </select>
          </div>
          <div class="form-group">
            <input type="submit" name="confirmOrder" value="Place Order" class="btn btn-success btn-block">
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

  $("#orderForm").submit(function(e) {
    e.preventDefault();
    $.ajax({
      url: 'allcode.php',
      type: 'POST',
      data: $('form').serialize() + "&action=placeOrder",
      success: function(response) {
        get_cart_count();
        $("#orderInfo").html(response);
        window.location.href = 'https://buy.stripe.com/test_bIYaFv11PdSo9xedQQ';
      }
    });
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