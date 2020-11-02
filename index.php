<?php
include('functions.php');
if (!isLoggedIn()) {
    $_SESSION['msg'] = "Debes identificarte primero";
    header('location: login.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página principal</title>
    
</head>
<body>
    <p>BIENVENIDO AL LOGIN</p>
   <!-- logged in user information -->
   <?php if (isset($_SESSION['user'])) : ?>
				<?php
				$user = $_SESSION[ 'user' ][ 'username' ];
                                $name = $_SESSION[ 'user' ][ 'name' ];
                                $surname = $_SESSION[ 'user' ][ 'surname' ];
                                $email = $_SESSION[ 'user' ][ 'email' ];
				?>
    <p><?php echo $user; ?></p>
    <?php endif ?>
</body>
</html>