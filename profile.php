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
    <title>Perfil de estudiante</title>
</head>

<body>
    <!-- logged in user information -->
    <?php if (isset($_SESSION['user'])) : ?>
        <?php
        $user = $_SESSION['user']['username'];
        $name = $_SESSION['user']['name'];
        $surname = $_SESSION['user']['surname'];
        $email = $_SESSION['user']['email'];
        $id = $_SESSION['user']['id'];
        ?>
        <?php include('includes/navbar.php'); ?>

        <div class="card">
            <div class="card-header">Perfil</div>
            <div class="card-body">
                <form name="my-form" action="profile.php" method="POST">
                    <?php if ($errors) : ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo display_error(); ?>
                        </div>
                    <?php endif; ?>

                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-right"></label>
                        <div class="col-md-6">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-right">ID</label>
                        <div class="col-md-6">
                            <input type="text" id="id" name="id" value="<?php echo $id; ?>" class="form-control" placeholder="<?php echo $id; ?>" readonly="true">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-right">User
                            Name</label>
                        <div class="col-md-6">
                            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username, ENT_QUOTES); ?>" class="form-control" placeholder="<?php echo $user; ?>" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-right">E-Mail
                            Address</label>
                        <div class="col-md-6">
                            <input type="text" id="email" name="email" value="<?php echo htmlspecialchars($email, ENT_QUOTES); ?>" class="form-control" placeholder="<?php echo $email; ?>" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-right">Contraseña*</label>
                        <div class="col-md-6">
                            <input type="password" id="password" name="password_1" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-right">Repite la contraseña*</label>
                        <div class="col-md-6">
                            <input type="password" id="password2" name="password_2" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-6 offset-md-4">
                        <button type="submit" class="btn btn-primary" name="update_btn">
                            Actualizar
                        </button>
                    </div>
            </div>
            </form>
        </div>
    <?php endif ?>
</body>

</html>