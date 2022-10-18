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
    <div id="message">
    </div>
  </div>
  <section>
    <div class="main-container" id="test2">
      <div class="form-container">
        <div class="forms">
          <div class="form prescription">
            <span class="title">Upload Prescription</span>
            <form id="prescriptionForm" enctype="multipart/form-data">
              <div class="form-group mb-2">
                <input type="hidden" name="user_id" value="<?= $_SESSION['auth_user']['user_id']; ?>"
                  class="form-control user_id" placeholder="user id">
              </div>
              <div class="form-group mb-2">
                <input type="hidden" name="user_name" value="<?= $_SESSION['auth_user']['user_name']; ?>"
                  class="form-control user_name" placeholder="user name">
              </div>
              <div class="form-group mb-2 input-field ">
                <input readonly type="tel" name="phoneNo" value="<?= $_SESSION['auth_user']['phoneNo']; ?>"
                  class="form-control phoneNo" placeholder="Phone Number">
              </div>
              <div class="form-group mb-2 input-field ">
                <input readonly type="email" name="email" value="<?= $_SESSION['auth_user']['email']; ?>"
                  class="form-control email" placeholder="Email">
              </div>
              <div class="form-group mb-3 input-field ">
                <input type="file" class="form-control prescription_image" id="prescription_image"
                  name="prescription_image" placeholder="Prescription Image" />
                <span id="error_prescriptionImg" class="text-danger ms-3"></span>
              </div>
              <div class="form-group mb-2 input-field ">
                <input type="email" name="hospital_email" class="form-control hospital_email" id="hospital_email"
                  placeholder="Hospital Email">
                <span id="error_hospitalemail" class="text-danger ms-3"></span>
              </div>
              <div class="form-group input-field button">
                <input type="hidden" name="action" id="action" value="uploadPrescription">
                <button type="submit" name="uploadBtn" class="uploadBtn">Upload</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<?php
include('includes/footer.php');
?>
<script>
$('document').ready(function() {
  $('#prescriptionForm').on('submit', function(e) {
    e.preventDefault();
    if ($('#prescription_image').val() == '') {
      error_prescriptionImg = 'Please select an image';
      $('#error_prescriptionImg').text(error_prescriptionImg);
    } else {
      error_prescriptionImg = '';
      $('#error_prescriptionImg').text(error_prescriptionImg);
    }

    if ($('#hospital_email').val() == '') {
      error_hospitalemail = 'Please enter your hospital email';
      $('#error_hospitalemail').text(error_hospitalemail);
    } else {
      error_hospitalemail = '';
      $('#error_hospitalemail').text(error_hospitalemail);
    }

    if (error_prescriptionImg != '' || error_hospitalemail != '') {
      return false;
    } else {
      var fd = new FormData(this);
      $.ajax({
        type: "POST",
        url: "allcode.php",
        data: fd,
        contentType: false,
        cache: false,
        processData: false,
        dataType: "json",
        success: function(response) {
          if (response == "Sent") {
            $('#message').html(
              '<div class="alert alert-success alert-dismissible fade show" role="alert"><strong>' +
              "Prescription has been uploaded successfully! Please wait for it to be processed. You will receive an email confirmation" +
              '</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
            );
            $(".alert").delay(2000).slideUp(400, function() {
              $(this).alert('close');
            });
            document.getElementById("prescriptionForm").reset();
          } else if (response == "Failed") {
            $('#message').html(
              '<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>' +
              "Prescription could not be added! Please Try again" +
              '</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
            );
            $(".alert").delay(1500).slideUp(400, function() {
              $(this).alert('close');
            });
            document.getElementById("prescriptionForm").reset();
          } else {
            $('#message').html(
              '<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>' +
              "Prescription could not be added! Please Try again" +
              '</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
            );
            $(".alert").delay(1500).slideUp(400, function() {
              $(this).alert('close');
            });
            document.getElementById("prescriptionForm").reset();
          }
        }
      });
    }
  });
});
</script>