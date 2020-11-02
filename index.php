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
    <!-- logged in user information -->
    <?php if (isset($_SESSION['user'])) : ?>
        <?php
        $user = $_SESSION['user']['username'];
        $name = $_SESSION['user']['name'];
        $surname = $_SESSION['user']['surname'];
        $email = $_SESSION['user']['email'];
        ?>
        <?php include('includes/navbar.php'); ?>

        <nav class="navbar navbar-light bg-light">
            <form class="form-inline">
                <a class="navbar-brand">Horarios</a>
                <button class="btn btn-outline-success mx-2" type="button">Día</button>
                <button class="btn btn-outline-success mx-2" type="button">Semana</button>
                <button class="btn btn-outline-success mx-2" type="button">Mes</button>
            </form>
        </nav>

        <p>REVISAR</p>
        <span>
        https://github.com/xavierocampos/Bootstrap-Calendar-PHP-MySQL
        </span>
        <p>
        https://dcblog.dev/jquery-fullcalender-with-php-and-mysql
        </p>


    <?php endif ?>
</body>

</html>