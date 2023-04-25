<?php
session_start();
if (isset($_SESSION["user"])) {
   header("Location: dashboard.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <!-- font-awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet"/>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet"/>
    <!-- MDB -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.2.0/mdb.min.css" rel="stylesheet" />
</head>
<body>
<section class="vh-100 bg-image">
  <div class="mask d-flex align-items-center h-100 gradient-custom-3">
    <div class="container h-100">
      <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col-12 col-md-9 col-lg-7 col-xl-6">
          <div class="card" style="border-radius: 15px;">
          <!-- card-header -->
            <div class="card-header">
                <div class="text-center">
                    <!-- <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-login-form/lotus.webp"
                      style="width: 185px;" alt="logo"> -->
                    <img src="image/logos.webp" alt="Description of the image"  width="300" height="150">
                    <h4 class="mt-1 mb-2 pb-1">We are The Lotus Team</h4>
                </div>
            </div>

            <!-- card-body -->
            <div class="card-body p-4">
                <!-- Pills navs -->
                <ul class="nav nav-pills nav-justified mb-3" id="ex1" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="tab-register" data-mdb-toggle="pill" href="#pills-register" role="tab"
                            aria-controls="pills-register" aria-selected="false">Register</a>
                        </li>
                        <li class="nav-item" role="presentation">
                        <a class="nav-link" id="tab-login" data-mdb-toggle="pill" href="#pills-login" role="tab"
                            aria-controls="pills-login" aria-selected="true">Login</a>
                    </li>
                </ul>
                
                <!-- pill-content -->
                <div class="tab-content">
                    <!-- log-in-tab -->
                    <div class="tab-pane fade" id="pills-login" role="tabpanel" aria-labelledby="tab-login">
                        <?php
                            if (isset($_POST["login"])) {
                            $email = $_POST["email"];
                            $password = $_POST["password"];
                                require_once "database.php";
                                $sql = "SELECT * FROM users WHERE email = '$email'";
                                $result = mysqli_query($conn, $sql);
                                $user = mysqli_fetch_array($result, MYSQLI_ASSOC);
                                if ($user) {
                                    if (password_verify($password, $user["password"])) {
                                        session_start();
                                        // $_SESSION["user"] = "yes";
                                        $_SESSION["user"] = $user["fullname"];
                                        header("Location: dashboard.php");
                                        die();
                                    }else{
                                        echo "<div class='alert alert-danger'>Password does not match
                                        </div>";
                                    }
                                }else{
                                    echo "<div class='alert alert-danger'>Email does not match
                                    </div>";
                                }
                            }
                        ?>
                        <form action="main.php" method="post">       
                            <!-- Email input -->
                            <div class="form-outline mb-4">
                            <input type="email" name="email" class="form-control" />
                            <label class="form-label" for="email">Enter Email:</label>
                            </div>
                    
                            <!-- Password input -->
                            <div class="form-outline mb-4">
                            <input type="password" name="password" class="form-control" />
                            <label class="form-label" for="password">Enter Password:</label>
                            </div>
                    
                            <!-- 2 column grid layout -->
                            <div class="row mb-4">
                            <div class="col-md-6 d-flex justify-content-center">
                            </div>
                    
                            <div class="col-md-6 d-flex justify-content-center">
                                <!-- Simple link -->
                                <a href="forgetpassword.php">Forgot password?</a>
                            </div>
                            </div>
                            <!-- Submit button -->
                            <button type="submit" name="login" class="btn btn-primary btn-block mb-4">LOG IN</button>
 
                        </form>
                    </div>

                    <!-- registration-tab -->
                    <div class="tab-pane fade show active" id="pills-register" role="tabpanel" aria-labelledby="tab-register">
                        <?php
                            if (isset($_POST["submit"])) {
                                $fullName = $_POST["fullname"];
                                $email = $_POST["email"];
                                $password = $_POST["password"];
                                $passwordRepeat = $_POST["repeat_password"];
                                
                                $passwordHash = password_hash($password, PASSWORD_DEFAULT);

                                $errors = array();
                                
                                if (empty($fullName) OR empty($email) OR empty($password) OR empty($passwordRepeat)) {
                                    array_push($errors,"All fields are required");
                                }
                                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                                    array_push($errors, "Email is not valid");
                                }
                                if (strlen($password)<8) {
                                    array_push($errors,"Password must be at least 8 charactes long");
                                }
                                if ($password!==$passwordRepeat) {
                                    array_push($errors,"Password does not match");
                                }
                                require_once "database.php";
                                $sql = "SELECT * FROM users WHERE email = '$email'";
                                $result = mysqli_query($conn, $sql);
                                $rowCount = mysqli_num_rows($result);
                                if ($rowCount>0) {
                                    array_push($errors,"Email already exists!");
                                }
                                if (count($errors)>0) {
                                    foreach ($errors as  $error) {
                                        echo "<div class='alert alert-danger'>$error
                                        </div>";
                                    }
                                }else{
                                    
                                    $sql = "INSERT INTO users (fullname, email, password) VALUES ( ?, ?, ? )";
                                    $stmt = mysqli_stmt_init($conn);
                                    $prepareStmt = mysqli_stmt_prepare($stmt,$sql);
                                    if ($prepareStmt) {
                                        mysqli_stmt_bind_param($stmt,"sss",$fullName, $email, $passwordHash);
                                        mysqli_stmt_execute($stmt);
                                        echo "<div class='alert alert-success'>You are registered successfully.</div>";
                                    }else{
                                        die("Something went wrong");
                                    }
                                } 

                            }
                        ?>
                        <form action="main.php" method="post">
                            <!-- Username input -->
                            <div class="form-outline mb-4">
                            <input type="text" name="fullname" class="form-control" />
                            <label class="form-label" for="fullname">Fullname</label>
                            </div>
                    
                            <!-- Email input -->
                            <div class="form-outline mb-4">
                            <input type="email" id="email" name="email" class="form-control" />
                            <label class="form-label" for="email">Email</label>
                            </div>
                    
                            <!-- Password input -->
                            <div class="form-outline mb-4">
                            <input type="password" class="form-control" id="password" name="password">
                            <label class="form-label" for="password">Password</label>
                            </div>
                    
                            <!-- Repeat Password input -->
                            <div class="form-outline mb-4">
                            <!--<input type="password" id="confirmPass" class="form-control" />-->
                            <input type="password" class="form-control" name="repeat_password">
                            <label class="form-label" for="repeat_password">Repeat password</label>
                            </div>

                            <!-- Submit button -->
                            <button type="submit" name="submit" id="submit" class="btn btn-primary btn-block mb-3">Sign in</button>
                        </form>
                    </div>
                </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- script -->
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.2.0/mdb.min.js"></script>
  </section>
    
</body>
</html>