<?php
session_start();

if (isset($_SESSION['auth'])) 
{
  if (!isset($_SESSION['message'])) {
    $_SESSION['message'] = "You are already logged in";
  }
  header("Location: login.php");
  exit(0);
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <meta name="description" content="" />
  <meta name="author" content="" />
  <title>Login</title>
  <link href="css/pagestyles.css" rel="stylesheet" />
  <link href="css/styles.css" rel="stylesheet" />

</head>

<body>

  <div class="container mt-2">
    <?php include('../message.php');?>
    <div id="error">
    </div>
  </div>

  <div class="main-container">
    <div class="form-container">
      <div class="forms">
        <div class="form login">
          <span class="title">Login</span>

          <form id="loginForm">
            <div class="form-group mb-2 input-field ">
              <input type="email" name="email" class="form-control" placeholder="Hospital Email">
            </div>

            <div class="form-group mb-3 input-field ">
              <input type="password" class="form-control" name="password" placeholder="Password">
            </div>
            <div class="form-group input-field button">
              <input type="submit" name="loginBtn" value="Login">
            </div>
          </form>

          <div class="login-signup">
            <span class="text">No account?
              <a href="#" class="text signup-link">Sign up Now</a>
            </span>
          </div>
        </div>

        <!-- Registration Form -->
        <div class="form signup">
          <span class="title">Registration</span>

          <form id="registerForm">
            <div class="input-field">
              <input type="text" name="first_name" placeholder="First Name">
            </div>
            <div class="input-field">
              <input type="text" name="last_name" placeholder="Last Name">
            </div>
            <div class="input-field">
              <input type="tel" name="phoneNo" placeholder="Phone Number">
            </div>
            <div class="input-field">
              <input type="email" name="email" placeholder="Hospital Email">
            </div>
            <div class="input-field">
              <input type="text" name="gender" placeholder="Gender">
            </div>
            <div class="input-field">
              <input type="password" name="password" placeholder="Password">
            </div>
            <div class="input-field button">
              <input type="submit" name="registerBtn" value="Register">
            </div>
          </form>
          <div class="login-signup">
            <span class="text">Have an account?
              <a href="#" class="text login-link">Login Now</a>
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
  </script>
  <script src="js/scripts.js"></script>
  <script src="js/jquery.min.js"></script>
  <script src="js/jquery.validate.min.js"></script>


  <script>
  const container = document.querySelector(".form-container"),
    signUp = document.querySelector(".signup-link"),
    login = document.querySelector(".login-link");

  // js code to appear signup and login form
  signUp.addEventListener("click", () => {
    container.classList.add("active");
  });
  login.addEventListener("click", () => {
    container.classList.remove("active");
  });
  </script>

  <script>
  $('document').ready(function() {
    $("form[id='loginForm']").validate({
      errorClass: 'error',
      rules: {
        email: {
          required: true,
          email: true,
          maxlength: 100,
        },
        password: "required",
      },
      messages: {
        email: "Enter a valid email",
        password: "Password is required",
      },
      submitHandler: function(form) {
        $.ajax({
          url: 'hospitallogincode.php',
          type: 'POST',
          data: $(form).serialize(),
          success: function(response) {
            if (response == "success") {
              alert("Login Successfull!");
              window.location.href = "home.php"
            } else if (response == "fail") {
              $('#error').html(
                '<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>' +
                "Please register first!" +
                '</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
              );
              $(".alert").delay(1500).slideUp(400, function() {
                $(this).alert('close');
              });
              document.getElementById("loginForm").reset();
            } else if (response == "failed") {
              $('#error').html(
                '<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>' +
                "Email doesn't exist" +
                '</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
              );
              $(".alert").delay(1500).slideUp(400, function() {
                $(this).alert('close');
              });
              document.getElementById("loginForm").reset();
            } else if (response == "invalid") {
              $('#error').html(
                '<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>' +
                "Email and Password do not match!" +
                '</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
              );
              $(".alert").delay(1500).slideUp(400, function() {
                $(this).alert('close');
              });
              document.getElementById("loginForm").reset();
            } else {
              $('#error').html(
                '<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>' +
                "You do not have access to this page!" +
                '</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
              );
              $(".alert").delay(1500).slideUp(400, function() {
                $(this).alert('close');
              });
              document.getElementById("loginForm").reset();
            }
          }
        });
      }
    });

    $("form[id='registerForm']").validate({
      errorClass: 'error',
      rules: {
        first_name: "required",
        last_name: "required",
        phoneNo: "required",
        email: {
          required: true,
          email: true,
          maxlength: 100,
        },
        gender: "required",
        password: "required",

      },
      messages: {
        first_name: "First name is required",
        last_name: "Last name is required",
        phoneNo: "Phone number is required",
        email: "Enter a valid email",
        gender: "Gender is required",
        password: "Password is required",

      },
      submitHandler: function(form) {
        $.ajax({
          url: 'hospitalregistercode.php',
          type: 'POST',
          data: $(form).serialize(),
          success: function(response) {
            if (response == "success") {
              alert("Registered Successfully!");
              document.getElementById("registerForm").reset();
              window.location.href = "login.php";
            } else if (response == "Failed") {
              $('#error').html(
                '<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>' +
                "Registration Failed! Please Try again" +
                '</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
              );
              document.getElementById("registerForm").reset();
            } else if (response == "Failed2") {
              $('#error').html(
                '<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>' +
                "Registration Failed! Please Try again" +
                '</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
              );
              document.getElementById("registerForm").reset();
            } else if (response == "emailFail") {
              $('#error').html(
                '<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>' +
                "Email Already Exists!" +
                '</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
              );
              document.getElementById("registerForm").reset();
            } else {
              $('#error').html(
                '<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>' +
                "Registration Failed! Please Try again" +
                '</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
              );
              document.getElementById("registerForm").reset();
            }
          }
        });
      }
    });
  });
  </script>

</body>

</html>