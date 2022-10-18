<?php
include('authentication.php');
include('includes/header.php');
?>

<div class="container-fluid px-4">
  <h1 class="mt-4">Dashboard</h1>
  <ol class="breadcrumb mb-4">
    <li class="breadcrumb-item active">Dashboard</li>
  </ol>
  <?php include('../message.php');?>

  <div class="row">
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border border-3 border-primary shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                Users</div>
              <?php
                    $query = "SELECT * FROM `tbl_users`";
                    $query_execute = mysqli_query($connection, $query);
                    $result = mysqli_num_rows($query_execute);
                ?>
              <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $result; ?></div>
            </div>
            <div class="col-auto">
              <i class="fas fa-users fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
        <div class="card-footer d-flex align-items-center justify-content-between">
          <a class="small text-dark stretched-link" href="registeredusers.php">View Details</a>
          <div class="small text-dark"><i class="fas fa-angle-right"></i></div>
        </div>
      </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border border-3 border-warning shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                Orders</div>
              <?php
                    $query = "SELECT * FROM `tbl_order`";
                    $query_execute = mysqli_query($connection, $query);
                    $result = mysqli_num_rows($query_execute);
                ?>
              <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $result; ?></div>
            </div>
            <div class="col-auto">
              <i class="fa-solid fa-file-lines fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
        <div class="card-footer d-flex align-items-center justify-content-between">
          <a class="small text-dark stretched-link" href="orders.php">View Details</a>
          <div class="small text-dark"><i class="fas fa-angle-right"></i></div>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border border-3 border-success shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                Products</div>
              <?php
                    $query = "SELECT * FROM `tbl_product`";
                    $query_execute = mysqli_query($connection, $query);
                    $result = mysqli_num_rows($query_execute);
                ?>
              <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $result; ?></div>
            </div>
            <div class="col-auto">
              <!-- <i class="fas fa-calendar fa-2x text-gray-300"></i> -->
              <i class="fa-solid fa-dolly fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
        <div class="card-footer d-flex align-items-center justify-content-between">
          <a class="small text-dark stretched-link" href="products.php">View Details</a>
          <div class="small text-dark"><i class="fas fa-angle-right"></i></div>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border border-3 border-info shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                Sales</div>
              <?php
                $query = "SELECT SUM(order_amount) as sales FROM `tbl_order`";
                $query_execute = mysqli_query($connection, $query);
                $row = mysqli_fetch_assoc($query_execute); 
                $sum = $row['sales'];
                ?>
              <div class="h5 mb-0 font-weight-bold text-gray-800">Ksh. <?php echo $sum; ?></div>
            </div>
            <div class="col-auto">
              <!-- <i class="fas fa-calendar fa-2x text-gray-300"></i> -->
              <i class="fa-solid fa-chart-line fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
        <div class="card-footer d-flex align-items-center justify-content-between">
          <a class="small text-dark stretched-link" href="#">View Details</a>
          <div class="small text-dark"><i class="fas fa-angle-right"></i></div>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border border-3 border-dark shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                Registered Hospitals</div>
              <?php
                    $query = "SELECT * FROM `tbl_hospitalstaff`";
                    $query_execute = mysqli_query($connection, $query);
                    $result = mysqli_num_rows($query_execute);
                ?>
              <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $result; ?></div>
            </div>
            <div class="col-auto">
              <i class="fas fa-file-medical fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
        <div class="card-footer d-flex align-items-center justify-content-between">
          <a class="small text-dark stretched-link" href="staffs.php">View Details</a>
          <div class="small text-dark"><i class="fas fa-angle-right"></i></div>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border border-3 border-dark shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                Uploaded Prescriptions</div>
              <?php
                    $query = "SELECT * FROM `tbl_prescription`";
                    $query_execute = mysqli_query($connection, $query);
                    $result = mysqli_num_rows($query_execute);
                ?>
              <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $result; ?></div>
            </div>
            <div class="col-auto">
              <i class="fas fa-file-medical fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
        <div class="card-footer d-flex align-items-center justify-content-between">
          <a class="small text-dark stretched-link" href="allprescriptions.php">View Details</a>
          <div class="small text-dark"><i class="fa-solid fa-file-upload"></i></div>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border border-3 border-secondary shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                Approved Prescriptions</div>
              <?php
                    $query = "SELECT * FROM `tbl_prescription` WHERE status = 'approved'";
                    $query_execute = mysqli_query($connection, $query);
                    $result = mysqli_num_rows($query_execute);
                ?>
              <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $result; ?></div>
            </div>
            <div class="col-auto">
              <i class="fas fa-file-medical fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
        <div class="card-footer d-flex align-items-center justify-content-between">
          <a class="small text-dark stretched-link" href="approvedprescriptions.php">View Details</a>
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