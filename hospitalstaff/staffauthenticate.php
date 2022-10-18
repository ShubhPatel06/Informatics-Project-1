<?php
session_start();
include('../config/db_connection.php');

if (!isset($_SESSION['auth'])) 
{
  $_SESSION['message'] = "Please login as Hospital Staff!";
  header("Location: login.php");
  exit(0);
}
else {
  if ($_SESSION['auth_role'] != "3" ) {
    // $_SESSION['message'] = "You are not authorized as a Hospital Staff!";
    // // header("Location: ../index.php");
    // // exit(0);

    unset($_SESSION['auth']);
    unset($_SESSION['auth_role']);
    unset($_SESSION['auth_user']);
  
    $_SESSION['message'] = "You are not authorized as a Hospital Staff!  Sorry you were logged out!";
    header("Location: login.php");
    exit(0);
  }
}
?>