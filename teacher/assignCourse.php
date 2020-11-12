<?php include('../functions.php') ?>
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Matrícula</title>

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
  <link rel="stylesheet" href="customStyle.css">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body id="page-top">
  <?php include("includes/session.php"); ?>

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <?php include("includes/drawerMenu.php") ?>

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

          <!-- Sidebar Toggle (Topbar) -->
          <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
          </button>

          <!-- Topbar Navbar -->
          <?php include("includes/topbar.php"); ?>

          <!-- End of Topbar -->

          <!-- Begin Page Content -->
          <div class="container-fluid">

            <main class="my-form">
              <div class="cotainer">
                <div class="row justify-content-center">
                  <div class="col-md-8">
                    <div class="card">
                      <div class="card-header">Matricular</div>
                      <div class="card-body">
                        <form name="my-form" action="assignCourse.php" method="POST">
                          <?php if ($errors) : ?>
                            <div class="alert alert-danger" role="alert">
                              <?php echo display_error(); ?>
                            </div>
                          <?php endif; ?>

                          <?php if ($success) : ?>
                            <div class="alert alert-success" role="alert">
                              <?php echo display_success(); ?>
                            </div>
                          <?php endif; ?>
                          <div class="form-group row">
                            <label class="col-md-4 col-form-label text-md-right"></label>
                            <div class="col-md-6">
                            </div>
                          </div>

                          <div class="form-group row">
                            <label class="col-md-4 col-form-label text-md-right">Curso</label>
                            <div class="col-md-6">
                              <select name="id_course" class="select-box">
                                <option value="0">Selecciona:</option>
                                <?php
                                $query = "SELECT * FROM courses";
                                $res_query = mysqli_query($db, $query);
                                while ($queryData = mysqli_fetch_array($res_query)) {
                                  echo '<option value="' . $queryData[id_course] . '">' . $queryData[name] . '</option>';
                                }
                                ?>
                              </select>
                            </div>
                          </div>


                          <div class="form-group row">
                            <label class="col-md-4 col-form-label text-md-right">Estudiante</label>
                            <div class="col-md-6">
                              <select name="id" class="select-box">
                                <option value="0">Selecciona:</option>
                                <?php
                                $query = "SELECT * FROM students";
                                $res_query = mysqli_query($db, $query);
                                while ($queryData = mysqli_fetch_array($res_query)) {
                                  echo '<option value="' . $queryData[id] . '">' . $queryData[name] . '</option>';
                                }
                                ?>
                              </select>
                            </div>
                          </div>

                          <div class="form-group row">
                            <label class="col-md-4 col-form-label text-md-right">Estado</label>
                            <div class="col-md-6">
                            <input type="checkbox" checked>
                            </div>
                          </div>

                          <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-primary" name="registerEnrollment_btn">
                              Matricular
                            </button>
                          </div>
                      </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
          </div>

          </main>
          <!-- End of Main Content -->

          <!-- Footer -->
          <?php include("includes/footer.php"); ?>
          <!-- End of Footer -->

      </div>
      <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
      <i class="fas fa-angle-up"></i>
    </a>

    <?php include("includes/scripts.php"); ?>

</body>

</html>