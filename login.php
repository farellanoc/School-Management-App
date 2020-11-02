<?php include('functions.php') ?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/login.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
  <title>Login</title>
</head>

<body>
  <div class="wrapper fadeInDown">
    <div id="formContent">

      <?php if ($errors) : ?>
        <div class="alert alert-danger" role="alert">
          <?php echo display_error(); ?>
        </div>
      <?php endif; ?>
      <!-- Login Form -->
      <form method="POST" action="login.php">
        <input type="text" class="fadeIn second" name="username" placeholder="Username">
        <input type="password" name="password" class="fadeIn third" placeholder="Password">
        <input type="submit" name="login_btn" class="fadeIn fourth" value="Log In">
      </form>

      <!-- Register-->
      <div id="formFooter">
        <a class="underlineHover" href="register.php">Regístrate</a>
      </div>

    </div>
  </div>
</body>

</html>