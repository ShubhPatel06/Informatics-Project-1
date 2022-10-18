<?php

$host = "localhost";
$username = "root";
$password = "";
$database = "mypharma";

$connection = mysqli_connect("$host", "$username", "$password", "$database");

// if(!$connection){
//   header("Location: ../errors/errors.php");
//   die();

// }
if ($connection -> connect_error) {
  echo "Failed to connect to MySQL: " . $connection -> connect_error;
  exit();
}

?>