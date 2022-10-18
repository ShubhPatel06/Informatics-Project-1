<?php
session_start();
include('config/db_connection.php');

if(isset($_POST['loginBtn']))
{
  $email = mysqli_real_escape_string($connection, $_POST['email']);
  $password = mysqli_real_escape_string($connection, $_POST['password']);

  // $login_query = "SELECT * FROM `tbl_users` WHERE `email` = '$email' AND `password` = '$password' LIMIT 1";
  $login_query = "SELECT * FROM `tbl_users` WHERE `email` = '$email'";

  $login_query_execute = mysqli_query($connection, $login_query);

  if (mysqli_num_rows($login_query_execute) > 0) {

    $getPassword = "SELECT * FROM `tbl_users` WHERE `email` = '$email'";
    $getPassword_execute = mysqli_query($connection, $getPassword);
    $row = mysqli_fetch_assoc($getPassword_execute);
    $pass = $row['password'];

    if (password_verify($password, $pass)) {
      foreach ($getPassword_execute as $userData) {
        $user_id = $userData['user_id'];
        $first_name = $userData['first_name'];
        $last_name = $userData['last_name'];
        $user_name = $userData['first_name'].' '.$userData['last_name'];
        $phoneNo = $userData['phoneNo'];
        $email = $userData['email'];
        $gender = $userData['gender'];
        $dob = $userData['dob'];
        $role = $userData['role']; // 1 = Customer, 2 = Admin
      }
      $_SESSION['auth'] = true;
      $_SESSION['auth_role'] = "$role";
      $_SESSION['auth_user'] = [
        'user_id'=> $user_id,
        'first_name'=> $first_name,
        'last_name'=> $last_name,
        'user_name'=> $user_name,
        'phoneNo'=> $phoneNo,
        'email'=> $email,
        'gender'=> $gender,
        'dob'=> $dob,
      ];
  
      if ($_SESSION['auth_role'] == '1') {
        echo "success";
      }
      elseif ($_SESSION['auth_role'] == '2') {
        echo "adminSuccess";
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