<?php include('server.php') ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>ORTADA</title>
  <link rel="stylesheet" href="style1.css" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
</head>
<body>

<div class="login-container">
    <img src="ortada.png" alt="ORTADA Riders Logo" class="logo" />
    <h2>Login</h2>

    <form method="post" action="login.php">
        <?php include('errors.php'); ?>
        <input type="text" name="username" placeholder="Username" required />
        <input type="password" name="password" placeholder="Password" required />
        <button type="submit" name="login_user">Login</button>
        <p>Not yet a member? <a href="register.php">Sign up</a></p>
    </form>
</div>

</body>
</html>
