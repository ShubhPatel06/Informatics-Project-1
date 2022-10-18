<?php
include('../config/db_connection.php');
include('authentication.php');
include('includes/header.php');

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

<div class="container">
  <div class="row justify-content-center">
    <div class="col-lg-6 px-4 pb-4 mt-5" id="orderInfo">
      <!-- <h4 class="text-center text-info p-2">Complete your order!</h4> -->
      <div class="jumbotron p-3 mb-2 text-center bg-light">
        <h6 class="lead"><b>Products : </b><?= $allItems; ?></h6>
        <h5><b>Amount to Pay : </b><?= number_format($grand_total,2) ?>/-</h5>
      </div>
      <form id="orderForm">
        <input type="hidden" name="products" value="<?= $allItems; ?>">
        <input type="hidden" name="grand_total" value="<?= $grand_total; ?>">
        <div class="form-group mb-2">
          <input type="number" name="customer_id" class="form-control customer_id" placeholder="Customer ID">
        </div>
        <div class="form-group mb-2">
          <input type="email" name="customer_email" class="form-control customer_email" placeholder="Customer Email">
        </div>
        <!-- <div class="form-group mb-2">
          <textarea name="address" class="form-control" rows="2" cols="10"
            placeholder="Enter Delivery Address Here..."></textarea>
        </div> -->

        <div class="form-group">
          <input type="submit" name="confirmOrder" value="Submit Order" class="btn btn-success btn-block">
        </div>
      </form>
    </div>
  </div>
</div>

<?php
include('includes/footer.php');
include('includes/scripts.php');
?>

<script>
$(document).ready(function() {

  $("#orderForm").submit(function(e) {
    e.preventDefault();
    $.ajax({
      url: 'crud.php',
      type: 'POST',
      data: $('form').serialize() + "&action=placeOrder",
      success: function(response) {
        $("#orderInfo").html(response);
        // window.location.href = 'https://buy.stripe.com/test_bIYaFv11PdSo9xedQQ';

      }
    });
  });

});
</script>