<?php

session_start();
// connect to database
$db = mysqli_connect('localhost', 'root', '', 'p6ddbb');
// variable declaration
$username = "";
$email = "";
$phone = "";
$name = "";
$nif = "";
$surname = "";
$errors = array();
$date = date('d-m-y');
$time = date('H:i');

// call the register() function if register_btn is clicked
if (isset($_POST['register_btn'])) {
    register();
}

//mostrar total usuarios
function total_usuarios($db)
{
    $result = $db->query("SELECT COUNT(*) FROM students");
    $row = $result->fetch_row();
    $total_users = $row[0];
    return $total_users;
}

// REGISTER USER
function register()
{
    // call these variables with the global keyword to make them available in function
    global $db, $errors, $username, $email;

    // receive all input values from the form. Call the e() function
    // defined below to escape form values
    $username = e($_POST['username']);
    $name = e($_POST['name']);
    $surname = e($_POST['surname']);
    $nif = e($_POST['nif']);
    $phone = e($_POST['phone']);
    $email = e($_POST['email']);
    $password_1 = e($_POST['password_1']);
    $password_2 = e($_POST['password_2']);
    $date = date('d-m-y');

    // form validation: ensure that the form is correctly filled
    if (empty($username)) {
        array_push($errors, "Se requiere un nombre de usuario");
    }
    if (empty($email)) {
        array_push($errors, "Se requiere un email");
    }
    if (empty($password_1)) {
        array_push($errors, "Se requiere una clave");
    }
    if ($password_1 != $password_2) {
        array_push($errors, "Las claves no coinciden");
    }
    //comprueba que no exista el usuario
    $check = "SELECT username FROM students WHERE username = '$username'";
    $res_check = mysqli_query($db, $check);
    if (mysqli_num_rows($res_check) >= 1) {
        array_push($errors, "El usuario ya existe");
    }
    //comprueba que no exista el correo
    $check = "SELECT email FROM students WHERE email = '$email'";
    $res_check = mysqli_query($db, $check);
    if (mysqli_num_rows($res_check) >= 1) {
        array_push($errors, "El correo ya existe");
    }

    //comprueba que no exista el nif
    $check = "SELECT nif FROM students WHERE nif = '$nif'";
    $res_check = mysqli_query($db, $check);
    if (mysqli_num_rows($res_check) >= 1) {
        array_push($errors, "El nif ya existe");
    }

    // register user if there are no errors in the form
    if (count($errors) == 0) {
        $password = md5($password_1); //encrypt the password before saving in the database
        $check = "SELECT * FROM students WHERE username = '$username'";
        $res_check = mysqli_query($db, $check);
        if (mysqli_num_rows($res_check) == 0) {
            $query = "INSERT INTO students (id, username, pass, email, name, surname, telephone, nif, date_registered) 
					  VALUES(NULL, '$username', '$password', '$email', '$name', '$surname', '$phone', '$nif', '$date')";
            mysqli_query($db, $query);
        }
        // get id of the created user
        $logged_in_user_id = mysqli_insert_id($db);

        $_SESSION['user'] = getUserById($logged_in_user_id); // put logged in user in session
        $_SESSION['success'] = "Bienvenid@!";
        header('location: index.php');
    }
}

//Update function

function updateUser()
{
    // call these variables with the global keyword to make them available in function
    global $db, $errors, $username, $email;

    // receive all input values from the form. Call the e() function
    // defined below to escape form values
    $username = e($_POST['username']);
    $email = e($_POST['email']);
    $id = e($_POST['id']);
    $password_1 = e($_POST['password_1']);
    $password_2 = e($_POST['password_2']);

    // form validation: ensure that the form is correctly filled
    if (empty($username)) {
        array_push($errors, "Se requiere un nombre de usuario");
    }
    if (empty($email)) {
        array_push($errors, "Se requiere un email");
    }
    if (empty($password_1)) {
        array_push($errors, "Se requiere una clave");
    }
    if ($password_1 != $password_2) {
        array_push($errors, "Las claves no coinciden");
    }

    // update user if there are no errors in the form
    if (count($errors) == 0) {
        $password = md5($password_1); //encrypt the password before saving in the database
        $query = "UPDATE students SET username = '$username', pass = '$password', email = '$email' WHERE id = '$id'";
        mysqli_query($db, $query);
    }
    header('location: login.php');
}
// return user array from their id
function getUserById($id)
{
    global $db;
    $query = "SELECT * FROM students WHERE id=" . $id;
    $result = mysqli_query($db, $query);

    $user = mysqli_fetch_assoc($result);
    return $user;
}

// escape string
function e($val)
{
    global $db;
    return mysqli_real_escape_string($db, trim($val));
}

function display_error()
{
    global $errors;

    if (count($errors) > 0) {
        echo '<div class="alert alert-small alert-round-medium bg-red2-dark">
                <i class="fa fa-times-circle"></i>
                <i class="fa fa-times"></i>';
        foreach ($errors as $error) {
            echo $error . '<br>';
        }
        echo '</div>';
    }
}

function isLoggedIn()
{
    if (isset($_SESSION['user'])) {
        return true;
    } else {
        return false;
    }
}

if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['user']);
    header("location: login.php");
}

// call the login() function if register_btn is clicked
if (isset($_POST['login_btn'])) {
    login();
}

// call the login() function if register_btn is clicked
if (isset($_POST['update_btn'])) {
    updateUser();
}

// LOGIN USER
function login()
{
    global $db, $username, $errors;

    // grab form values
    $username = e($_POST['username']);
    $password = e($_POST['password']);

    // make sure form is filled properly
    if (empty($username)) {
        array_push($errors, "Se requiere nombre de usuario");
    }
    if (empty($password)) {
        array_push($errors, "Se requiere una clave");
    }

    // attempt login if no errors on form
    if (count($errors) == 0) {
        $password = md5($password);

        $query = "SELECT * FROM students WHERE username='$username' AND pass='$password' LIMIT 1";
        $results = mysqli_query($db, $query);

        if (mysqli_num_rows($results) == 1) { // user found
            $logged_in_user = mysqli_fetch_assoc($results);
            $_SESSION['user'] = $logged_in_user;
            $_SESSION['success'] = "Bienvenid@!";
            header('location: index.php');
        }
    } else {
        array_push($errors, "La clave o el usuario no coinciden");
    }
}
