<?php include('server.php') ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>ORTADA - Register</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style1.css" />
</head>
<body>

<div class="register-container">
  <img src="ortada.png" alt="ORTADA Riders Logo" class="logo" />
  <h2>Register</h2>

  <form method="post" action="register.php">
    <?php include('errors.php'); ?>
    
    <input type="text" name="username" placeholder="Username" value="<?php echo $username; ?>" required />
    
    <input type="email" name="email" placeholder="Email" value="<?php echo $email; ?>" required />
    
    <input type="password" name="password_1" placeholder="Password" required />
    
    <input type="password" name="password_2" placeholder="Confirm Password" required />
    
    <button type="submit" name="reg_user">Register</button>

    <p>Already a member? <a href="login.php">Sign in</a></p>
  </form>
</div>

</body>
</html>
