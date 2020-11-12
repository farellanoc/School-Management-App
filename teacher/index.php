<?php
include('../functions.php');
if (!isTeacher()) {
  $_SESSION['msg'] = "Debes ser profesor";
  header('location: login.php');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Panel de profesor</title>

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body id="page-top">
  <!-- logged in admin information -->
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

            <!-- Content Row -->
            <div class="row">

              <!-- Classes -->
              <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                  <div class="card-body">
                    <div class="row no-gutters align-items-center">
                      <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Asignaturas</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                          <table id="table_log" class="table-borders">

                            <thead>
                              <tr>
                                <th>ID</th>
                                <th>&nbsp; &nbsp; &nbsp;</th>
                                <th>Asignatura</th>
                              </tr>
                            </thead>
                            <tbody>
                              <!-- Carga toda la información que asociará a las columnas -->
                              <?php
                              $query = "SELECT * FROM class";
                              $res = mysqli_query($db, $query);
                              while ($row = mysqli_fetch_assoc($res)) {
                              ?>
                                <tr style="color:grey">
                                  <td><?php echo $row['id_class'] ?></td>
                                  <td></td>
                                  <td><?php echo $row['name'] ?></td>
                                </tr>
                              <?php
                              }
                              ?>
                            </tbody>
                          </table>
                        </div>
                      </div>
                      <div class="col-auto">
                        <i class="fas fa-calendar fa-2x text-gray-300"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Courses -->
              <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                  <div class="card-body">
                    <div class="row no-gutters align-items-center">
                      <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Cursos</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                          <table id="table_log" class="table-borders">

                            <thead>
                              <tr>
                                <th>ID</th>
                                <th>&nbsp; &nbsp; &nbsp;</th>
                                <th>Cursos</th>
                              </tr>
                            </thead>
                            <tbody>
                              <!-- Carga toda la información que asociará a las columnas -->
                              <?php
                              $query = "SELECT * FROM courses";
                              $res = mysqli_query($db, $query);
                              while ($row = mysqli_fetch_assoc($res)) {
                              ?>
                                <tr style="color:grey">
                                  <td><?php echo $row['id_course'] ?></td>
                                  <td></td>
                                  <td><?php echo $row['name'] ?></td>
                                </tr>
                              <?php
                              }
                              ?>
                            </tbody>
                          </table>
                        </div>
                      </div>
                      <div class="col-auto">
                        <i class="fas fa-calendar fa-2x text-gray-300"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>


              <!-- Students -->
              <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                  <div class="card-body">
                    <div class="row no-gutters align-items-center">
                      <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Estudiantes</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">

                          <table id="table_log" class="table-borders">

                            <thead>
                              <tr>
                                <th>ID</th>
                                <th>&nbsp; &nbsp; &nbsp;</th>
                                <th>Estudiante</th>
                              </tr>
                            </thead>
                            <tbody>
                              <!-- Carga toda la información que asociará a las columnas -->
                              <?php
                              $query = "SELECT * FROM students";
                              $res = mysqli_query($db, $query);
                              while ($row = mysqli_fetch_assoc($res)) {
                              ?>
                                <tr style="color:grey">
                                  <td><?php echo $row['id'] ?></td>
                                  <td></td>
                                  <td><?php echo $row['username'] ?></td>
                                </tr>
                              <?php
                              }
                              ?>
                            </tbody>
                          </table>
                        </div>
                      </div>
                      <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- /.container-fluid -->

            </div>
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