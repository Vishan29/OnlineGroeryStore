<?php

@include 'config.php';

session_start();

if(!isset($user_id)){
    header('location:login.php');
}

if(isset($_POST['submit'])){

   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $pass = md5($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);

   $sql = "SELECT name FROM `users` WHERE email = ? AND password = ?";
   $stmt = $conn->prepare($sql);
   $stmt->execute([$email, $pass]);
   $name = mysqli_fetch_array($sql);

}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="style.css">
    <title>Welcome to GroceryMart</title>
</head>
<body>
    <header>
		<img src="logo.png" alt="logo">
		<h1>GroceryMart</h1>
	</header>
    <?php echo "<h1>Welcome " . $_SESSION['name'] . "</h1>"; ?>
    <a href="logout.php" class="logout">Logout</a>
</body>
</html>