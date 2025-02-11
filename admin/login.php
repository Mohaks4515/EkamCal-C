<?php
  $error="";

  include('../includes/conn.php');

  session_start();

  if(isset($_POST['submit'])){
    $username = $_POST['username'];
    $password = $_POST['password'];

    if(isset($username) && isset($password)){
      $query="SELECT * FROM login WHERE username='$username' and password='$password'";
      $result=mysqli_query($conn,$query);
      $row=mysqli_fetch_array($result);
      $count=mysqli_num_rows($result);

      if($count==1){
        $_SESSION['user']=$username;
        header("location: dashboard.php");
      }else{
        $error = 'Please enter correct username/password';
      }
    }
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Login</title>
  <link rel="stylesheet" href="/School/style/style.css">
</head>
<body>
<div class="login-form">
  <form method="POST" action="">
    <h1>Admin Login</h1>
    <div class="content">
      <div class="input-field">
        <input type="username" placeholder="Username" name="username" autocomplete="nope" required>
      </div>
      <div class="input-field">
        <input type="password" placeholder="Password" name="password" autocomplete="new-password" required>
      </div>
      <?php echo $error ?>
    </div>
    <div class="action">
      <button type="submit" name="submit">Login</button>
    </div>
  </form>
</div>
</body>
</html>
