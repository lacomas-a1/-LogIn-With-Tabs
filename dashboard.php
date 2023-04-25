<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: main.php");
    die();
}
// display user's dashboard content here
?>
<!DOCTYPE html>
<html>
<head>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <title>User Dashboard</title>
</head>
</head>
<body>
    <h1>Welcome to your dashboard</h1>
    <!-- add your dashboard content here -->
    <p>You are logged in as <?php echo $_SESSION["user"]; ?></p>
    <a href="logout.php" class="btn btn-warning">Logout</a>
</body>
</html>
