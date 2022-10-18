<?php
// session_start();
include('staffauthenticate.php');
include('includes/header.php');
?>

<div class="container-fluid px-4">
  <h1 class="mt-4">Dashboard</h1>
  <ol class="breadcrumb mb-4">
    <li class="breadcrumb-item active">Dashboard</li>
  </ol>
  <div class="row">
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border border-3 border-primary shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                Prescriptions</div>
              <?php
                    $email= $_SESSION['auth_user']['email'];
                    $query = "SELECT * FROM `tbl_prescription` WHERE hospital_email = '$email'";
                    $query_execute = mysqli_query($connection, $query);
                    $result = mysqli_num_rows($query_execute);
                ?>
              <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $result; ?></div>
            </div>
            <div class="col-auto">
              <i class="fas fa-file-medical"></i>
            </div>
          </div>
        </div>
        <div class="card-footer d-flex align-items-center justify-content-between">
          <a class="small text-dark stretched-link" href="prescriptions.php">View Details</a>
          <div class="small text-dark"><i class="fas fa-angle-right"></i></div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php
include('includes/footer.php');
include('includes/scripts.php');
?>