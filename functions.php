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
$errors_delete_class = array();
$success = array();
$success_delete_class = array();
$date = date('d-m-y');
$time = date('H:i');
$date_start = "";
$date_end ="";

//mostrar total profesores
function getTeachers($db)
{
    $result = $db->query("SELECT COUNT(*) FROM teachers");
    $row = $result->fetch_row();
    $total_users = $row[0];
    return $total_users;
}

//mostrar total estudiantes
function getStudents($db)
{
    $result = $db->query("SELECT COUNT(*) FROM students");
    $row = $result->fetch_row();
    $total_users = $row[0];
    return $total_users;
}

//mostrar total cursos
function getCourses($db)
{
    $result = $db->query("SELECT COUNT(*) FROM courses");
    $row = $result->fetch_row();
    $total_users = $row[0];
    return $total_users;
}

//mostrar total clases
function getClasses($db)
{
    $result = $db->query("SELECT COUNT(*) FROM class");
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

// REGISTER ADMIN
function registerAdmin()
{
    // call these variables with the global keyword to make them available in function
    global $db, $errors, $username, $email, $success;

    // receive all input values from the form. Call the e() function
    // defined below to escape form values
    $username = e($_POST['username']);
    $name = e($_POST['name']);
    $email = e($_POST['email']);
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
    //comprueba que no exista el usuario
    $check = "SELECT username FROM users_admin WHERE username = '$username'";
    $res_check = mysqli_query($db, $check);
    if (mysqli_num_rows($res_check) >= 1) {
        array_push($errors, "El usuario ya existe");
    }
    //comprueba que no exista el correo
    $check = "SELECT email FROM users_admin WHERE email = '$email'";
    $res_check = mysqli_query($db, $check);
    if (mysqli_num_rows($res_check) >= 1) {
        array_push($errors, "El correo ya existe");
    }

    // register user if there are no errors in the form
    if (count($errors) == 0) {
        $password = md5($password_1); //encrypt the password before saving in the database
        $check = "SELECT * FROM users_admin WHERE username = '$username'";
        $res_check = mysqli_query($db, $check);
        if (mysqli_num_rows($res_check) == 0) {
            $query = "INSERT INTO users_admin (id_user_admin, username, name, email, password) 
					  VALUES(NULL, '$username', '$name', '$email', '$password')";
            mysqli_query($db, $query);
        }
        // get id of the created user
        $logged_in_user_id = mysqli_insert_id($db);

        $_SESSION['admin'] = getUserById($logged_in_user_id); // put logged in user in session
        $_SESSION['success'] = "Administrador creado correctamente";
        array_push($success, "Registrado correctamente");
    }
}

// REGISTER TEACHER
function registerTeacher()
{
    // call these variables with the global keyword to make them available in function
    global $db, $errors, $username, $email, $success;

    // receive all input values from the form. Call the e() function
    // defined below to escape form values
    $name = e($_POST['name']);
    $surname = e($_POST['surname']);
    $phone = e($_POST['telephone']);
    $nif = e($_POST['nif']);
    $email = e($_POST['email']);
    $username = e($_POST['username']);
    $password_1 = e($_POST['password_1']);
    $password_2 = e($_POST['password_2']);


    // form validation: ensure that the form is correctly filled
    if (empty($surname)) {
        array_push($errors, "Se requiere un apellido");
    }
    if (empty($email)) {
        array_push($errors, "Se requiere un email");
    }
    if (empty($name)) {
        array_push($errors, "Se requiere un nombre");
    }
    if (empty($phone)) {
        array_push($errors, "Se requiere un teléfono");
    }
    if (empty($nif)) {
        array_push($errors, "Se requiere un DNI");
    }
    if (empty($username)) {
        array_push($errors, "Se requiere un nombre de usuario");
    }
    if (empty($password_1)) {
        array_push($errors, "Se requiere una clave");
    }
    if ($password_1 != $password_2) {
        array_push($errors, "Las claves no coinciden");
    }
    //comprueba que no exista el usuario
    $check = "SELECT nif FROM teachers WHERE nif = '$nif'";
    $res_check = mysqli_query($db, $check);
    if (mysqli_num_rows($res_check) >= 1) {
        array_push($errors, "El usuario ya existe");
    }
    //comprueba que no exista el usuario
    $check = "SELECT username FROM teachers WHERE username = '$username'";
    $res_check = mysqli_query($db, $check);
    if (mysqli_num_rows($res_check) >= 1) {
        array_push($errors, "El usuario ya existe");
    }
    // register user if there are no errors in the form
    if (count($errors) == 0) {
        $password = md5($password_1); //encrypt the password before saving in the database
        $check = "SELECT * FROM teachers WHERE nif = '$nif'";
        $res_check = mysqli_query($db, $check);
        if (mysqli_num_rows($res_check) == 0) {
            $query = "INSERT INTO teachers (id_teacher, username, pass, name, surname, telephone, nif, email) 
					  VALUES(NULL, '$username','$password','$name', '$surname','$phone','$nif', '$email')";
            mysqli_query($db, $query);
        }
        array_push($success, "Profesor registrado correctamente");
    }
}


// REGISTER Course
function registerCourse()
{
    // call these variables with the global keyword to make them available in function
    global $db, $errors, $success;

    // receive all input values from the form. Call the e() function
    // defined below to escape form values
    $name = e($_POST['name']);
    $description = e($_POST['description']);
    $date_start = e($_POST['date_start']);
    $date_end = e($_POST['date_end']);

    // form validation: ensure that the form is correctly filled
    if (empty($date_start)) {
        array_push($errors, "Se requiere una fecha de inicio");
    }
    if (empty($description)) {
        array_push($errors, "Se requiere una descripción");
    }
    if (empty($name)) {
        array_push($errors, "Se requiere un nombre");
    }
    if (empty($date_end)) {
        array_push($errors, "Se requiere una fecha final");
    }

    if (count($errors) == 0) {
        $check = "SELECT * FROM courses WHERE name = '$name'";
        $res_check = mysqli_query($db, $check);
        if (mysqli_num_rows($res_check) == 0) {
            $query = "INSERT INTO courses (id_course, name, description, date_start, date_end, active) 
					  VALUES(NULL, '$name', '$description','$date_start','$date_end', 1)";
            mysqli_query($db, $query);
        }
        array_push($success, "Curso registrado correctamente");
    }
}

// REGISTER Class
function registerClass()
{
    // call these variables with the global keyword to make them available in function
    global $db, $errors, $success;

    // receive all input values from the form. Call the e() function
    // defined below to escape form values
    $id_teacher = e($_POST['id_teacher']);
    $id_course = e($_POST['id_course']);
    $id_schedule = e($_POST['id_schedule']);
    $name = e($_POST['name']);
    $color = e($_POST['color']);

    // form validation: ensure that the form is correctly filled
    if (empty($id_teacher)) {
        array_push($errors, "Se requiere un id de profesor");
    }
    if (empty($id_course)) {
        array_push($errors, "Se requiere un id de curso");
    }
    if (empty($name)) {
        array_push($errors, "Se requiere un nombre");
    }
    if (empty($color)) {
        array_push($errors, "Se requiere un color");
    }
    if (empty($name)) {
        array_push($errors, "Se requiere un ");
    }
    if (empty($id_schedule)) {
        array_push($errors, "Se requiere un id de horario");
    }

    if (count($errors) == 0) {
        $check = "SELECT * FROM class WHERE name = '$name'";
        $res_check = mysqli_query($db, $check);
        if (mysqli_num_rows($res_check) == 0) {
            $query = "INSERT INTO class (id_class, id_teacher, id_course, id_schedule, name, color) 
					  VALUES(NULL,'$id_teacher','$id_course','$id_schedule','$name','$color')";
            mysqli_query($db, $query);
        }
        array_push($success, "Asignatura registrada correctamente");
    }
}

//Update class
function updateClass()
{
    // call these variables with the global keyword to make them available in function
    global $db, $errors, $success;

    // receive all input values from the form. Call the e() function
    // defined below to escape form values
    $id_class = e($_POST['id_class']);
    $id_teacher = e($_POST['id_teacher']);
    $id_course = e($_POST['id_course']);
    $id_schedule = e($_POST['id_schedule']);
    $name = e($_POST['name']);
    $color = e($_POST['color']);

    // form validation: ensure that the form is correctly filled
    if (empty($id_class)) {
        array_push($errors, "Se requiere un id de asignatura");
    }
    if (empty($id_teacher)) {
        array_push($errors, "Se requiere un id de profesor");
    }
    if (empty($id_course)) {
        array_push($errors, "Se requiere un id de curso");
    }
    if (empty($id_schedule)) {
        array_push($errors, "Se requiere un id de horario");
    }
    if (empty($name)) {
        array_push($errors, "Se requiere un nombre");
    }
    if (empty($color)) {
        array_push($errors, "Se requiere un color");
    }


    if (count($errors) == 0) {
        $check = "SELECT * FROM class WHERE id_class = $id_class";
        $res_check = mysqli_query($db, $check);

        if (mysqli_num_rows($res_check) > 0) {
            $query = "UPDATE class SET id_teacher = '$id_teacher', id_course = '$id_course', id_schedule = '$id_schedule', name = '$name', color = '$color'
                                        WHERE id_class = '$id_class'";
            if($result = mysqli_query($db, $query) == true){
                array_push($success, "Asignatura modificada correctamente");
            }
        }
        if (mysqli_num_rows($res_check) ==  0){
            array_push($success, "No se ha encontrado ninguna asignatura con esa ID");
        }
    }
}
// call the updateClass() function if updateClass_btn is clicked
if (isset($_POST['updateClass_btn'])) {
    updateClass();
}
// Delete Class
function deleteClass()
{
    // call these variables with the global keyword to make them available in function
    global $db, $errors, $success_delete_class;

    // receive all input values from the form. Call the e() function
    // defined below to escape form values
    $id_class = e($_POST['id_class']);


    // form validation: ensure that the form is correctly filled
    if (empty($id_class)) {
        array_push($success_delete_class, "Se requiere un id de asignatura");
    }


    if (count($errors) == 0) {
        $check = "SELECT * FROM class WHERE id_class = $id_class";
        $res_check = mysqli_query($db, $check);

        if (!$res_check || mysqli_num_rows($res_check) > 0) {
            $query = "delete from class where id_class  = $id_class";
            if($result = mysqli_query($db, $query) == true){
                array_push($success_delete_class, "Asignatura eliminada correctamente");
            }
        }
        if (!$res_check || mysqli_num_rows($res_check) == 0) {
            $query = "delete from class where id_class  = $id_class";
            if($result = mysqli_query($db, $query) == true){
                array_push($success_delete_class, "No se ha encontrado ninguna asignatura con ese ID");
            }
        }
    }
}
// call the deleteCLass() function if deleteClass_btn is clicked
if (isset($_POST['deleteClass_btn'])) {
    deleteClass();
}

//Update Course
function updateCourse()
{
    // call these variables with the global keyword to make them available in function
    global $db, $errors, $success;

    // receive all input values from the form. Call the e() function
    // defined below to escape form values
    $id_course = e($_POST['id_course']);
    $name = e($_POST['name']);
    $description = e($_POST['description']);
    $date_start = e($_POST['date_start']);
    $date_end = e($_POST['date_end']);
    $active = e($_POST['active']);

    // form validation: ensure that the form is correctly filled
    if (empty($id_course)) {
        array_push($errors, "Se requiere un id de curso");
    }
    if (empty($name)) {
        array_push($errors, "Se requiere un nombre");
    }
    if (empty($description)) {
        array_push($errors, "Se requiere una descripcion");
    }
    if (empty($date_start)) {
        array_push($errors, "Se requiere una fecha de inicio");
    }
    if (empty($date_end)) {
        array_push($errors, "Se requiere una fecha de finalización");
    }
    if (empty($active)) {
        array_push($errors, "Se requiere saber si esta activo");
    }


    if (count($errors) == 0) {
        $check = "SELECT * FROM courses WHERE id_course = $id_course";
        $res_check = mysqli_query($db, $check);

        if (mysqli_num_rows($res_check) > 0) {
            $query = "UPDATE courses SET name = '$name', description = '$description', active = '$active' 
                                        WHERE id_course = '$id_course'";
            if($result = mysqli_query($db, $query) == true){
                array_push($success, "Asignatura modificada correctamente");
            }
        }
        if (mysqli_num_rows($res_check) ==  0){
            array_push($success, "No se ha encontrado ningun curso con esta ID");
        }
    }
}
// call the updateCourse() function if updateCourse_btn is clicked
if (isset($_POST['updateCourse_btn'])) {
    updateCourse();
}

//Delete course
function deleteCourse()
{
    // call these variables with the global keyword to make them available in function
    global $db, $errors, $success_delete_class;

    // receive all input values from the form. Call the e() function
    // defined below to escape form values
    $id_course = e($_POST['id_course']);


    // form validation: ensure that the form is correctly filled
    if (empty($id_course)) {
        array_push($success_delete_class, "Se requiere un id de curso");
    }


    if (count($errors) == 0) {
        $check = "SELECT * FROM courses WHERE id_course = $id_course";
        $res_check = mysqli_query($db, $check);

        if (!$res_check || mysqli_num_rows($res_check) >= 1) {
            $query = "delete from courses where id_course  = $id_course";
            if ($result = mysqli_query($db, $query) == true) {
                array_push($success_delete_class, "Curso eliminado correctamente");
            }
        }
        if (!$res_check || mysqli_num_rows($res_check) == 0) {
            $query = "delete from courses where id_course  = $id_course";
            if ($result = mysqli_query($db, $query) == true) {
                array_push($success_delete_class, "No se ha encontrado ningun curso con esa ID");
            }
        }
    }
}
// call the deleteCourse() function if deleteCourse_btn is clicked
    if (isset($_POST['deleteCourse_btn'])) {
        deleteCourse();
    }
//Update teacher
function updateTeacher()
{
    // call these variables with the global keyword to make them available in function
    global $db, $errors, $success;

    // receive all input values from the form. Call the e() function
    // defined below to escape form values
    $id_teacher = e($_POST['id_teacher']);
    $name = e($_POST['name']);
    $surname = e($_POST['surname']);
    $telephone = e($_POST['telephone']);
    $nif = e($_POST['nif']);
    $email = e($_POST['email']);

    // form validation: ensure that the form is correctly filled
    if (empty($id_teacher)) {
        array_push($errors, "Se requiere un id de profesor");
    }
    if (empty($name)) {
        array_push($errors, "Se requiere un nombre");
    }
    if (empty($surname)) {
        array_push($errors, "Se requiere un apellido");
    }
    if (empty($telephone)) {
        array_push($errors, "Se requiere un teléfono");
    }
    if (empty($nif)) {
        array_push($errors, "Se requiere un DNI");
    }
    if (empty($email)) {
        array_push($errors, "Se requiere un E-mail");
    }


    if (count($errors) == 0) {
        $check = "SELECT * FROM teachers WHERE id_teacher = $id_teacher";
        $res_check = mysqli_query($db, $check);

        if (mysqli_num_rows($res_check) > 0) {
            $query = "UPDATE teachers SET name = '$name', surname = '$surname', telephone = '$telephone', nif = '$telephone', email = '$email'
                                        WHERE id_teacher = '$id_teacher'";
            if($result = mysqli_query($db, $query) == true){
                array_push($success, "Profesor modificado correctamente");
            }
        }
        if (mysqli_num_rows($res_check) ==  0){
            array_push($success, "No se ha encontrado ningun profesor con esta ID");
        }
    }
}
// call the updateTeacher() function if updateTeacher_btn is clicked
if (isset($_POST['updateTeacher_btn'])) {
    updateTeacher();
}

//Delete Teacher
    function deleteTeacher()
    {
        // call these variables with the global keyword to make them available in function
        global $db, $errors, $success_delete_class;

        // receive all input values from the form. Call the e() function
        // defined below to escape form values
        $id_teacher = e($_POST['id_teacher']);


        // form validation: ensure that the form is correctly filled
        if (empty($id_teacher)) {
            array_push($success_delete_class, "Se requiere un id de profesor");
        }


        if (count($errors) == 0) {
            $check = "SELECT * FROM teachers WHERE id_teacher = $id_teacher";
            $res_check = mysqli_query($db, $check);

            if (!$res_check || mysqli_num_rows($res_check) > 0) {
                $query = "delete from teachers where id_teacher  = $id_teacher";
                if ($result = mysqli_query($db, $query) == true) {
                    array_push($success_delete_class, "Profesor eliminado correctamente");
                }
            }
            if (!$res_check || mysqli_num_rows($res_check) == 0) {
                $query = "delete from teachers where id_teacher  = $id_teacher";
                if ($result = mysqli_query($db, $query) == true) {
                    array_push($success_delete_class, "No se ha encontrado ningún profesor con esta ID");
                }
            }
        }
    }

// call the deleteTeacher() function if deleteTeacher_btn is clicked
    if (isset($_POST['deleteTeacher_btn'])) {
        deleteTeacher();
    }


// REGISTER Schedule
    function registerSchedule()
    {
        // call these variables with the global keyword to make them available in function
        global $db, $errors, $success;

        // receive all input values from the form. Call the e() function
        // defined below to escape form values
        $id_class = e($_POST['id_class']);
        $time_start = e($_POST['time_start']);
        $time_end = e($_POST['time_end']);
        $day = e($_POST['day']);

        // form validation: ensure that the form is correctly filled
        if (empty($id_class)) {
            array_push($errors, "Se requiere un id de asignatura");
        }
        if (empty($time_start)) {
            array_push($errors, "Se requiere una hora de inicio");
        }
        if (empty($time_end)) {
            array_push($errors, "Se requiere una hora de fin");
        }
        if (empty($day)) {
            array_push($errors, "Se requiere un día");
        }

        if (count($errors) == 0) {
            $check = "SELECT * FROM schedule WHERE id_class = '$id_class'";
            $res_check = mysqli_query($db, $check);
            if (mysqli_num_rows($res_check) == 0) {
                $query = "INSERT INTO schedule (id_schedule, id_class, time_start, time_end, day) 
                      VALUES(NULL,'$id_class','$time_start','$time_end','$day')";
            }
            mysqli_query($db, $query);
            array_push($success, "Horario registrado correctamente");
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

    function display_error_class()
    {
        global $errors_delete_class;

        if (count($errors_delete_class) > 0) {
            echo '<div class="alert alert-small alert-round-medium bg-red2-dark">
                <i class="fa fa-times-circle"></i>
                <i class="fa fa-times"></i>';
            foreach ($errors_delete_class as $error) {
                echo $error . '<br>';
            }
            echo '</div>';
        }
    }

    function display_success()
    {
        global $success;

        if (count($success) > 0) {
            echo '<div class="alert alert-small alert-round-medium bg-green-dark">
                <i class="fa fa-times-circle"></i>
                <i class="fa fa-times"></i>';
            foreach ($success as $succs) {
                echo $succs . '<br>';
            }
            echo '</div>';
        }
    }

    function display_success_delete_class()
    {
        global $success_delete_class;

        if (count($success_delete_class) > 0) {
            echo '<div class="alert alert-small alert-round-medium bg-green-dark">
                <i class="fa fa-times-circle"></i>
                <i class="fa fa-times"></i>';
            foreach ($success_delete_class as $succs) {
                echo $succs . '<br>';
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

function isTeacher()
{
    if (isset($_SESSION['teacher'])) {
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

    function isAdmin()
    {
        if (isset($_SESSION['admin'])) {
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


// call the register() function if register_btn is clicked
    if (isset($_POST['register_btn'])) {
        register();
    }

// call the registerAdmin() function if register_btn is clicked
    if (isset($_POST['registerAdmin_btn'])) {
        registerAdmin();
    }

// call the registerTeacher() function if register_btn is clicked
    if (isset($_POST['registerTeacher_btn'])) {
        registerTeacher();
    }

// call the registerCourse() function if registerCourse_btn is clicked
    if (isset($_POST['registerCourse_btn'])) {
        registerCourse();
    }

// call the registerClass() function if registerClass_btn is clicked
    if (isset($_POST['registerClass_btn'])) {
        registerClass();
    }

// call the registerSchedule() function if registerSchedule_btn is clicked
    if (isset($_POST['registerSchedule_btn'])) {
        registerSchedule();
    }

// call the updateUser() function if update_btn is clicked
    if (isset($_POST['update_btn'])) {
        updateUser();
    }

// call the login() function if register_btn is clicked
    if (isset($_POST['login_btn'])) {
        login();
    }

// call the loginAdmin() function if register_btn is clicked
    if (isset($_POST['loginAdmin_btn'])) {
        loginAdmin();
    }

// call the loginTeacher() function if register_btn is clicked
if (isset($_POST['loginTeacher_btn'])) {
    loginTeacher();
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

// LOGIN ADMIN
    function loginAdmin()
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

            $query = "SELECT * FROM users_admin WHERE username='$username' AND password='$password' LIMIT 1";
            $results = mysqli_query($db, $query);

            if (mysqli_num_rows($results) == 1) { // user found
                $logged_in_admin = mysqli_fetch_assoc($results);
                $_SESSION['admin'] = $logged_in_admin;
                $_SESSION['success'] = "Bienvenid@!";
                header('location: index.php');
            }
        } else {
            array_push($errors, "La clave o el usuario no coinciden");
        }
    }

            

// LOGIN TEACHER
function loginTeacher()
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

        $query = "SELECT * FROM teachers WHERE username='$username' AND pass='$password' LIMIT 1";
        $results = mysqli_query($db, $query);

        if (mysqli_num_rows($results) == 1) { // user found
            $logged_in_teacher = mysqli_fetch_assoc($results);
            $_SESSION['teacher'] = $logged_in_teacher;
            $_SESSION['success'] = "Bienvenid@!";
            header('location: index.php');
        }
    } else {
        array_push($errors, "La clave o el usuario no coinciden");
    }
}
