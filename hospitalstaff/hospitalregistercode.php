<?php
session_start();
include('../config/db_connection.php');

if(isset($_POST['registerBtn'])){

  $fname = mysqli_real_escape_string($connection, $_POST['first_name']);
  $lname =mysqli_real_escape_string($connection, $_POST['last_name']);
  $phoneNo = mysqli_real_escape_string($connection, $_POST['phoneNo']);
  $email = mysqli_real_escape_string($connection, $_POST['email']);
  $gender = mysqli_real_escape_string($connection, $_POST['gender']);
  $password = mysqli_real_escape_string($connection, $_POST['password']);
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Check if user with same email exists
    $checkEmail = "SELECT `hospital_email` FROM `tbl_hospitalstaff` WHERE `hospital_email` = '$email'";
    $checkEmail_run = mysqli_query($connection, $checkEmail);

    if (mysqli_num_rows($checkEmail_run) > 0) {
      echo "emailFail";
    }
    else {
      $insert_staff = "INSERT INTO `tbl_hospitalstaff` (`first_name`, `last_name`, `phoneNo`, `hospital_email`, `gender`, `password`, `role`) VALUES ('$fname','$lname', '$phoneNo', '$email', '$gender', '$hashed_password', '3')";
      $query_execute = mysqli_query($connection, $insert_staff);

      if ($query_execute) {
        echo "success";
      }
      else {
        echo "Failed";
      }
    }

}
else{
  header("Location: login.php");
  exit(0);
}
?>