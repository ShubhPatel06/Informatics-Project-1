<?php
session_start();
include('../config/db_connection.php');

if(isset($_POST['loginBtn']))
{
  $email = mysqli_real_escape_string($connection, $_POST['email']);
  $password = mysqli_real_escape_string($connection, $_POST['password']);

  // Check if hospital staff already exists
  $login_query = "SELECT * FROM `tbl_hospitalstaff` WHERE `hospital_email` = '$email'";
  $login_query_execute = mysqli_query($connection, $login_query);

  if (mysqli_num_rows($login_query_execute) > 0) {

    $getPassword = "SELECT * FROM `tbl_hospitalstaff` WHERE `hospital_email` = '$email'";
    $getPassword_execute = mysqli_query($connection, $getPassword);
    $row = mysqli_fetch_assoc($getPassword_execute);
    $pass = $row['password'];

    if (password_verify($password, $pass)) {
      foreach ($getPassword_execute as $staffData) {
        $staff_id = $staffData['staff_id'];
        $first_name = $staffData['first_name'];
        $last_name = $staffData['last_name'];
        $user_name = $staffData['first_name'].' '.$staffData['last_name'];
        $phoneNo = $staffData['phoneNo'];
        $email = $staffData['hospital_email'];
        $gender = $staffData['gender'];
        $role = $staffData['role']; // 1 = Customer, 2 = Admin, 3 = Hospital Staff
      }
      $_SESSION['auth'] = true;
      $_SESSION['auth_role'] = "$role";
      $_SESSION['auth_user'] = [
        'staff_id'=> $staff_id,
        'first_name'=> $first_name,
        'last_name'=> $last_name,
        'user_name'=> $user_name,
        'phoneNo'=> $phoneNo,
        'email'=> $email,
        'gender'=> $gender,
      ];
  
      if ($_SESSION['auth_role'] == '3') {
        echo "success";
      }
      else {
        echo "fail";
      }
    }
    else {
      echo "invalid";
    }
  }
  else {
    echo "failed";
  }
  
}
else{
  echo "noAccess";
}
?>