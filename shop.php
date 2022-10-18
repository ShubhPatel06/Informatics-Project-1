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
    <ul class="nav nav-tabs">
      <li class="nav-item">
        <a class="nav-link link-dark" aria-current="page" href="shop.php">All Products</a>
      </li>
      <li class="nav-item dropdown d-flex">
        <?php
$getCategories = "SELECT * FROM `tbl_categories`";
$getCategories_execute = mysqli_query($connection, $getCategories);
$rows = array();
while ($result = mysqli_fetch_array($getCategories_execute)) {
  $rows[] = $result;
}
      foreach ($rows as $key => $value) {
        ?>
        <a class="nav-link dropdown-toggle categoryLink link-dark" data-bs-toggle="dropdown" href="#" role="button"
          aria-expanded="false"
          data-category-id="<?php echo $value['category_id'] ?>"><?php echo $value['category_name'] ?></a>
        <?php
}
      ?>
        <ul class="dropdown-menu" id="testing">
        </ul>
      </li>

    </ul>
  </div>

  <div class="container mt-2">
    <div id="message"></div>
  </div>

  <!-- product section -->
  <section class="product_section layout_padding">
    <div class="container">
      <div class="heading_container heading_center">
        <h2>
          Our <span>products</span>
        </h2>
      </div>

      <div class="row" id="test2">

        <!-- Getting products -->
        <?php
$getProducts = "SELECT * FROM `tbl_product`";
$getProducts_execute = mysqli_query($connection, $getProducts);

while ($result = mysqli_fetch_array($getProducts_execute)) {
        ?>

        <?php
        if ($result['available_quantity'] == 0) {
        ?>
        <div class="col-sm-5 col-md-3 col-lg-3">
          <div class="box">
            <div class="option_container">
              <div class="options">
                <a class="option1 addprescription" data-product-id="<?php echo $result['product_id']; ?>"
                  data-product-name="<?php echo $result['product_name']; ?>"
                  data-unit-price="<?php echo $result['unit_price']; ?>">
                  <i class="fas fa-times"></i> Out of stock
                </a>
                <a class="option2 viewdetails" data-product-id="<?php echo $result['product_id']; ?>">
                  <i class="fas fa-eye"></i> View Details
                </a>
              </div>
            </div>
            <div class="img-box">
              <img src="<?php echo('admin/uploads/'.$result['product_image']); ?>" alt="">
            </div>
            <div class="detail-box">
              <h5>
                <?php echo $result['product_name']; ?>
              </h5>
              <h6>
                Ksh.<?php echo $result['unit_price']; ?>
              </h6>
              <input type="hidden" class="product_quantity" id="<?php echo $result['product_id']; ?>" value="1">
            </div>
          </div>
        </div>
        <?php
        } else if($result['available_quantity'] > 0 && $result['medicine_type'] == 2) {
?>
        <div class="col-sm-5 col-md-3 col-lg-3">
          <div class="box">
            <div class="option_container">
              <div class="options">
                <a class="option1 addprescription" href="uploadprescription.php"
                  data-product-id="<?php echo $result['product_id']; ?>"
                  data-product-name="<?php echo $result['product_name']; ?>"
                  data-unit-price="<?php echo $result['unit_price']; ?>">
                  <i class="fas fa-file-medical"></i> Add Prescription
                </a>
                <a class="option2 viewdetails" data-product-id="<?php echo $result['product_id']; ?>">
                  <i class="fas fa-eye"></i> View Details
                </a>
              </div>
            </div>
            <div class="img-box">
              <img src="<?php echo('admin/uploads/'.$result['product_image']); ?>" alt="">
            </div>
            <div class="detail-box">
              <h5>
                <?php echo $result['product_name']; ?>
              </h5>
              <h6>
                Ksh.<?php echo $result['unit_price']; ?>
              </h6>
              <input type="hidden" class="product_quantity" id="<?php echo $result['product_id']; ?>" value="1">
            </div>
          </div>
        </div>

        <?php
        } else {
?>
        <div class="col-sm-5 col-md-3 col-lg-3">
          <div class="box">
            <div class="option_container">
              <div class="options">
                <a class="option1 addtocart" data-product-id="<?php echo $result['product_id']; ?>"
                  data-product-name="<?php echo $result['product_name']; ?>"
                  data-unit-price="<?php echo $result['unit_price']; ?>">
                  <i class="fas fa-shopping-bag"></i> Add to cart
                </a>
                <a class="option2 viewdetails" data-product-id="<?php echo $result['product_id']; ?>">
                  <i class="fas fa-eye"></i> View Details
                </a>
              </div>
            </div>
            <div class="img-box">
              <img src="<?php echo('admin/uploads/'.$result['product_image']); ?>" alt="">
            </div>
            <div class="detail-box">
              <h5>
                <?php echo $result['product_name']; ?>
              </h5>
              <h6>
                Ksh.<?php echo $result['unit_price']; ?>
              </h6>
              <input type="hidden" class="product_quantity" id="<?php echo $result['product_id']; ?>" value="1">
            </div>
          </div>
        </div>
        <?php
      }
      ?>
        <?php
}
      ?>
      </div>
    </div>
  </section>
  <!-- end product section -->

</div>
<?php
include('includes/footer.php');
?>

<script>
$(document).ready(function() {
  $(document).on('click', '.categoryLink', function() {

    // var category_id = $(this).val();
    var category_id = $(this).data('category-id');
    var action = 'get_subcategory';
    // console.log(category_id);

    $.ajax({
      url: "allcode.php",
      type: "POST",
      data: {
        'category_id': category_id,
        'action': action
      },
      dataType: "json",
      success: function(data) {
        var html = '';
        $.each(data, function(key, value) {
          html += '<li><a class="dropdown-item subcategory" href="#" data-subcategory-id="' + value
            .subcategory_id + '">' + value.subcategory_name + '</a></li>';
        });
        $('#testing').html(html);
      }
    });
  });

  $(document).on('click', '.subcategory', function() {
    var subcategory_id = $(this).data('subcategory-id');
    var action = 'get_product';

    $.ajax({
      url: "allcode.php",
      type: "POST",
      data: {
        'subcategory_id': subcategory_id,
        'action': action
      },
      dataType: "json",
      success: function(data) {
        // console.log(data);
        var html = '';
        $.each(data, function(key, value) {
          if (value.available_quantity == 0) {
            html +=
              '<div class="col-sm-5 col-md-3 col-lg-3"><div class="box"><div class="option_container"><div class="options"><a class="option1 " data-product-id="' +
              value.product_id + '" data-product-name="' + value.product_name +
              '" data-unit-price="' + value.unit_price +
              '"><i class="fas fa-times"></i> Out of Stock</a><a class="option2 viewdetails"><i class="fas fa-eye"></i> View Details</a></div></div><div class="img-box"><img src="admin/uploads/' +
              value.product_image + '" alt=""></div><div class="detail-box"><h5>' +
              value.product_name + '</h5><h6>Ksh.' + value.unit_price +
              '</h6><input type="hidden" class="product_quantity" id="' + value.product_id +
              '" value="1"></div></div></div>';
            // $('#test2').html(html);
          } else if (value.available_quantity > 0 && value.medicine_type == 2) {
            html +=
              '<div class="col-sm-5 col-md-3 col-lg-3"><div class="box"><div class="option_container"><div class="options"><a class="option1 addprescription" href="uploadprescription.php" data-product-id="' +
              value.product_id + '" data-product-name="' + value.product_name +
              '" data-unit-price="' + value.unit_price +
              '"><i class="fas fa-file-medical"></i> Add Prescription</a><a class="option2 viewdetails" data-product-id="' +
              value.product_id +
              '"><i class="fas fa-eye"></i> View Details</a></div></div><div class="img-box"><img src="admin/uploads/' +
              value.product_image + '" alt=""></div><div class="detail-box"><h5>' +
              value.product_name + '</h5><h6>Ksh.' + value.unit_price +
              '</h6><input type="hidden" class="product_quantity" id="' + value.product_id +
              '" value="1"></div></div></div>';
            // $('#test2').html(html);
          } else {
            html +=
              '<div class="col-sm-5 col-md-3 col-lg-3"><div class="box"><div class="option_container"><div class="options"><a class="option1 addtocart" data-product-id="' +
              value.product_id + '" data-product-name="' + value.product_name +
              '" data-unit-price="' + value.unit_price +
              '"><i class="fas fa-shopping-bag"></i> Add to cart</a><a class="option2 viewdetails" data-product-id="' +
              value.product_id +
              '"><i class="fas fa-eye"></i> View Details</a></div></div><div class="img-box"><img src="admin/uploads/' +
              value.product_image + '" alt=""></div><div class="detail-box"><h5>' +
              value.product_name + '</h5><h6>Ksh.' + value.unit_price +
              '</h6><input type="hidden" class="product_quantity" id="' + value.product_id +
              '" value="1"></div></div></div>';
            // $('#test2').html(html);
          }
        });
        $('#test2').html(html);
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
      url: "allcode.php",
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
          // window.location.href = '';
          // $('#' + product_id).val('');
          $(".alert").delay(1500).slideUp(400, function() {
            $(this).alert('close');
          });
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

  $(document).on('click', '.viewdetails', function() {
    var product_id = $(this).data('product-id');
    var action = 'get_singleproduct';

    $.ajax({
      url: "allcode.php",
      type: "POST",
      data: {
        'product_id': product_id,
        'action': action
      },
      dataType: "json",
      success: function(data) {
        // console.log(data);
        var html = '';
        $.each(data, function(key, value) {
          if (value.available_quantity == 0) {
            html +=
              '<div class="product-container"><div class="left-column"><img width="100%" src="admin/uploads/' +
              value.product_image +
              '" alt=""></div><div class="right-column"><div class="product-description"><h1>' + value
              .product_name + '</h1><p>' + value.product_description +
              '</p></div><div class="product-price"><span>Ksh. ' + value.unit_price +
              '</span><a class="cart-btn " data-product-id="' +
              value.product_id + '" data-product-name="' + value.product_name +
              '" data-unit-price="' + value.unit_price +
              '"><i class="fas fa-times"></i> Out of Stock</a></div></div></div>';
          } else if (value.available_quantity > 0 && value.medicine_type == 2) {
            html +=
              '<div class="product-container"><div class="left-column"><img width="100%" src="admin/uploads/' +
              value.product_image +
              '" alt=""></div><div class="right-column"><div class="product-description"><h1>' + value
              .product_name + '</h1><p>' + value.product_description +
              '</p></div><div class="product-price"><span>Ksh. ' + value.unit_price +
              '</span><a href="uploadprescription.php" class="cart-btn addPrescription" data-product-id="' +
              value.product_id + '" data-product-name="' + value.product_name +
              '" data-unit-price="' + value.unit_price +
              '"><i class="fas fa-file-medical"></i> Add Prescription</a></div></div></div>';
          } else {
            html +=
              '<div class="product-container"><div class="left-column"><img width="100%" src="admin/uploads/' +
              value.product_image +
              '" alt=""></div><div class="right-column"><div class="product-description"><h1>' + value
              .product_name + '</h1><p>' + value.product_description +
              '</p></div><div class="product-price"><span>Ksh. ' + value.unit_price +
              '</span><input type="number" class="product_quantity" id="' + value.product_id +
              '" value="1"><a class="cart-btn addtocart" data-product-id="' +
              value.product_id + '" data-product-name="' + value.product_name +
              '" data-unit-price="' + value.unit_price +
              '"><i class="fas fa-shopping-bag"></i> Add to cart</a></div></div></div>';
          }
        });
        $('#test2').html(html);
      }
    });
  });
});
</script>