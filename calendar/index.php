<?php
include('../functions.php');
if (!isLoggedIn()) {
    $_SESSION['msg'] = "Debes identificarte primero";
    header('location: login.php');
}

$week=$_GET['lweek'];
$week=stripslashes($week);
if(empty($week)) {
$week = date('Y').'-W'.date('W');
}
$weekFilter = $week;
$year = explode('-',$week)[0];
$week = str_replace('W','',explode('-',$week)[1]);
$week_start_date = (new DateTime())->setISODate($year, $week);
$week_start_date->setTime(0, 0,0,0);
$week_end_date = (new DateTime())->setISODate($year, $week,7);
$week_end_date->setTime(0, 0,0,0);

$sql = "select
		sb.id_subject,
	sb.name as subject_name,
	sb.color as subject_color,
	s.day scheduled_days,
	s.time_start,
	s.time_end,
	c.name as course_name,
	c.date_start,
	c.date_end
from
	subjects as sb
inner join schedule s on
	s.id_subject = sb.id_subject
inner join courses c on
	s.id_course = c.id_course
inner join course_subjects cs on
	cs.id_course = s.id_course
	and cs.id_subject = s.id_subject
inner join enrollment e on
	e.id_course = s.id_course
where
    c.active = 1
    and ('".$week_start_date->format('Y-m-d')."' >= c.date_start and '".$week_start_date->format('Y-m-d')."' <=  c.date_end
	or '".$week_end_date->format('Y-m-d')."' >= c.date_start and '".$week_end_date->format('Y-m-d')."' <= c.date_end
	or c.date_start >= '".$week_start_date->format('Y-m-d')."' and c.date_start <= '".$week_end_date->format('Y-m-d')."'
	or c.date_end >= '".$week_start_date->format('Y-m-d')."' and c.date_end <='".$week_end_date->format('Y-m-d')."') 
	and e.id_student = ".$_SESSION['user_id']." 
	order by s.time_start asc";

$result = mysqli_query($db, $sql);
$rows = array();
while($r = mysqli_fetch_assoc($result)) {
    $rows[] = $r;
}
$calendar = ['m'=>[],'t'=>[],'w'=>[],'r'=>[],'f'=>[],'s'=>[],'u'=>[]];
$dayNumber = ['m'=>1,'t'=>2,'w'=>3,'r'=>4,'f'=>5,'s'=>6,'u'=>7];

try {
    foreach($rows as $row){
        if(empty($row)){
            continue;
        }
        $days = explode("|",$row['scheduled_days']);
        foreach($days as $day){
            $gendate = new DateTime();
            $gendate->setISODate($year,$week,$dayNumber[$day]); //year , week num , day
            $gendate->setTime(0, 0,0,0);
            $course_start_date = DateTime::createFromFormat('Y-m-d', $row['date_start']);
            $course_start_date->setTime(0, 0,0,0);
            $course_end_date = DateTime::createFromFormat('Y-m-d', $row['date_end']);
            $course_end_date->setTime(0, 0,0,0);
            $data = null;
            $curseInProgress = $gendate>=$course_start_date & $gendate<=$course_end_date;
            if($curseInProgress){
                $data = [
                    'subject_id'=>$row['id_subject'],
                    'subject_name'=>$row['subject_name'],
                    'subject_color'=>$row['subject_color'],
                    'course_name'=>$row['course_name'],
                    'time_start'=>$row['time_start'],
                    'time_end'=>$row['time_end'],
                ];
            }
            $calendar[$day][] = [
                'date'=>$gendate->format('d-m-Y'),
                'data'=>$data
            ];
        }
    }
}catch(Exception $ex){

}

if(empty($idSubject)){
    $idSubject = $rows['id_subject'];
}
if(empty($idCourse)){
    $idCourse = $rows['id_course'];
}

$sqlSubjects = "SELECT * FROM subjects";

$resultSubject = mysqli_query($db, $sqlSubjects);

$sqlCourses = "SELECT * FROM courses";

$resultCourses  = mysqli_query($db, $sqlCourses);

?>
<html>
<head>
  <!-- Custom fonts for this template-->
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script>document.getElementsByTagName("html")[0].className += " js";</script>
  <link rel="stylesheet" href="../../../assets/css/style.css">
  <title>Schedule Template | CodyHouse</title>
  <link href="../../../source/fonts/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>

  <link rel="stylesheet" type="text/css" href="../admin/bootstrap-admin-theme.css>
  <link rel="stylesheet" type="text/css" href="../admin/source/css/fonts.css">
  <link rel="stylesheet" type="text/css" href="../admin/source/css/main-classes.css">
  <script src = "JS/login_logout.js"></script>
</head>
<body id="page-top">

<!-- Page Wrapper -->
<div id="wrapper">


  <!-- Sidebar -->
  <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
      <div class="sidebar-brand-icon rotate-n-15">
        <i class="fas fa-graduation-cap"></i>
      </div>
      <div class="sidebar-brand-text mx-3">School System </div>
    </a>
    <?php include '../../menu-left-no-main.php';?>
  </ul>
  <!-- End of Sidebar -->

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
        <ul class="navbar-nav ml-auto">

          <!-- Nav Item - Search Dropdown (Visible Only XS) -->
          <li class="nav-item dropdown no-arrow d-sm-none">
            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="fas fa-search fa-fw"></i>
            </a>
            <!-- Dropdown - Messages -->
            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in" aria-labelledby="searchDropdown">
              <form class="form-inline mr-auto w-100 navbar-search">
                <div class="input-group">
                  <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
                  <div class="input-group-append">
                    <button class="btn btn-primary" type="button">
                      <i class="fas fa-search fa-sm"></i>
                    </button>
                  </div>
                </div>
              </form>
            </div>
          </li>

          <div class="topbar-divider d-none d-sm-block"></div>

          <!-- Nav Item - User Information -->
          <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo $check." ";?></span>
              <img class="img-profile rounded-circle" src="https://icon-library.net/images/google-user-icon/google-user-icon-12.jpg">
            </a>
            <!-- Dropdown - User Information -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                Logout
              </a>
            </div>
          </li>

        </ul>

      </nav>
      <!-- End of Topbar -->
      <div class="justify-content-center">
          <div class="cd-schedule margin-top-lg margin-bottom-lg js-cd-schedule">
              <form method="get" style="margin-left: 61px;margin-bottom: 5px;">
                  <input type="week" id="lweek" name="lweek" value="<?php echo $weekFilter; ?>">
                  <input type="submit" value="Filtrar">
              </form>
                  <div class="cd-schedule__events">
                      <ul>
                          <li class="cd-schedule__group">
                              <div class="cd-schedule__top-info">
                                  <ul>
                                      <li><span>Lunes</span></li>
                                      <span style="font-size:10px"><?php echo $calendar['m'][0]['date'];?></span>
                                  </ul>
                              </div>
                              <ul>
                                  <?php foreach ($calendar['m'] as $event): ?>
                                      <?php if(is_null($event['data'])){continue;} ?>
                                      <li class="cd-schedule__event">
                                          <a style="background-color: <?php echo $event['data']['subject_color'];?>" data-start="<?php echo $event['data']['time_start'];?>" data-end="<?php echo $event['data']['time_end'];?>" data-content="event-abs-circuit" data-event="event-1" >
                                              <p style="color:white;font-size:10px;"><?php echo $event['data']['course_name'];?></p>
                                              <em class="cd-schedule__name"><?php echo $event['data']['subject_name'];?></em>
                                          </a>
                                      </li>
                                  <?php endforeach; ?>
                              </ul>
                          </li>

                          <li class="cd-schedule__group">
                              <div class="cd-schedule__top-info">
                                  <ul>
                                      <li><span>Martes</span></li>
                                      <span style="font-size:10px"><?php echo $calendar['t'][0]['date'];?></span>
                                  </ul>
                              </div>

                              <ul>
                                  <?php foreach ($calendar['t'] as $event): ?>
                                      <?php if(is_null($event['data'])){continue;} ?>
                                      <li class="cd-schedule__event">
                                          <a style="background-color: <?php echo $event['data']['subject_color'];?>" data-start="<?php echo $event['data']['time_start'];?>" data-end="<?php echo $event['data']['time_end'];?>" data-content="event-abs-circuit" data-event="event-1" >
                                              <p style="color:white;font-size:10px;"><?php echo $event['data']['course_name'];?></p>
                                              <em class="cd-schedule__name"><?php echo $event['data']['subject_name'];?></em>
                                          </a>
                                      </li>
                                  <?php endforeach; ?>
                              </ul>
                          </li>

                          <li class="cd-schedule__group">
                              <div class="cd-schedule__top-info">
                                  <ul>
                                      <li><span>Miercoles</span></li>
                                      <span style="font-size:10px"><?php echo $calendar['w'][0]['date'];?></span>
                                  </ul>
                              </div>

                              <ul>
                                  <?php foreach ($calendar['w'] as $event): ?>
                                      <?php if(is_null($event['data'])){continue;} ?>
                                      <li class="cd-schedule__event">
                                          <a style="background-color: <?php echo $event['data']['subject_color'];?>" data-start="<?php echo $event['data']['time_start'];?>" data-end="<?php echo $event['data']['time_end'];?>" data-content="event-abs-circuit" data-event="event-1" >
                                              <p style="color:white;font-size:10px;"><?php echo $event['data']['course_name'];?></p>
                                              <em class="cd-schedule__name"><?php echo $event['data']['subject_name'];?></em>
                                          </a>
                                      </li>
                                  <?php endforeach; ?>
                              </ul>
                          </li>

                          <li class="cd-schedule__group">
                              <div class="cd-schedule__top-info">
                                  <ul>
                                      <li><span>Jueves</span></li>
                                      <span style="font-size:10px"><?php echo $calendar['r'][0]['date'];?></span>
                                  </ul>
                              </div>

                              <ul>
                                  <?php foreach ($calendar['r'] as $event): ?>
                                      <?php if(is_null($event['data'])){continue;} ?>
                                      <li class="cd-schedule__event">
                                          <a style="background-color: <?php echo $event['data']['subject_color'];?>" data-start="<?php echo $event['data']['time_start'];?>" data-end="<?php echo $event['data']['time_end'];?>" data-content="event-abs-circuit" data-event="event-1" >
                                              <p style="color:white;font-size:10px;"><?php echo $event['data']['course_name'];?></p>
                                              <em class="cd-schedule__name"><?php echo $event['data']['subject_name'];?></em>
                                          </a>
                                      </li>
                                  <?php endforeach; ?>
                              </ul>
                          </li>

                          <li class="cd-schedule__group">
                              <div class="cd-schedule__top-info">
                                  <ul>
                                      <li><span>Viernes</span></li>
                                      <span style="font-size:10px"><?php echo $calendar['f'][0]['date'];?></span>
                                  </ul>
                              </div>

                              <ul>
                                  <?php foreach ($calendar['f'] as $event): ?>
                                      <?php if(is_null($event['data'])){continue;} ?>
                                      <li class="cd-schedule__event">
                                          <a style="background-color: <?php echo $event['data']['subject_color'];?>" data-start="<?php echo $event['data']['time_start'];?>" data-end="<?php echo $event['data']['time_end'];?>" data-content="event-abs-circuit" data-event="event-1" >
                                              <p style="color:white;font-size:10px;"><?php echo $event['data']['course_name'];?></p>
                                              <em class="cd-schedule__name"><?php echo $event['data']['subject_name'];?></em>
                                          </a>
                                      </li>
                                  <?php endforeach; ?>
                              </ul>
                          </li>
                          <li class="cd-schedule__group">
                              <div class="cd-schedule__top-info">
                                  <ul>
                                      <li><span>Sábado</span></li>
                                      <span style="font-size:10px"><?php echo $calendar['s'][0]['date'];?></span>
                                  </ul>
                              </div>

                              <ul>
                                  <?php foreach ($calendar['s'] as $event): ?>
                                      <?php if(is_null($event['data'])){continue;} ?>
                                      <li class="cd-schedule__event">
                                          <a style="background-color: <?php echo $event['data']['subject_color'];?>" data-start="<?php echo $event['data']['time_start'];?>" data-end="<?php echo $event['data']['time_end'];?>" data-content="event-abs-circuit" data-event="event-1" >
                                              <p style="color:white;font-size:10px;"><?php echo $event['data']['course_name'];?></p>
                                              <em class="cd-schedule__name"><?php echo $event['data']['subject_name'];?></em>
                                          </a>
                                      </li>
                                  <?php endforeach; ?>
                              </ul>
                          </li>
                          <li class="cd-schedule__group">
                              <div class="cd-schedule__top-info">
                                  <ul>
                                      <li><span>Domingo</span></li>
                                      <span style="font-size:10px"><?php echo $calendar['u'][0]['date'];?></span>
                                  </ul>
                              </div>

                              <ul>
                                  <?php foreach ($calendar['u'] as $event): ?>
                                      <?php if(is_null($event['data'])){continue;} ?>
                                      <li class="cd-schedule__event">
                                          <a style="background-color: <?php echo $event['data']['subject_color'];?>" data-start="<?php echo $event['data']['time_start'];?>" data-end="<?php echo $event['data']['time_end'];?>" data-content="event-abs-circuit" data-event="event-1" >
                                              <p style="color:white;font-size:10px;"><?php echo $event['data']['course_name'];?></p>
                                              <em class="cd-schedule__name"><?php echo $event['data']['subject_name'];?></em>
                                          </a>
                                      </li>
                                  <?php endforeach; ?>
                              </ul>
                          </li>
                      </ul>
                  </div>

                  <div class="cd-schedule-modal">
                      <header class="cd-schedule-modal__header">
                          <div class="cd-schedule-modal__content">
                              <span class="cd-schedule-modal__date"></span>
                              <h3 class="cd-schedule-modal__name"></h3>
                          </div>

                          <div class="cd-schedule-modal__header-bg"></div>
                      </header>

                      <div class="cd-schedule-modal__body">
                          <div class="cd-schedule-modal__event-info"></div>
                          <div class="cd-schedule-modal__body-bg"></div>
                      </div>

                      <a href="#0" class="cd-schedule-modal__close text-replace">Close</a>
                  </div>

                  <div class="cd-schedule__cover-layer"></div>
              </div>
        </div> <!-- .cd-schedule -->

        <script src="../../../assets/js/util.js"></script> <!-- util functions included in the CodyHouse framework -->
        <script src="../../../assets/js/main.js"></script>
      </div>
    </div>
    <!-- End of Main Content -->
  </div>
  <!-- End of Content Wrapper -->

</div>
<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Preparado para salir?</h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">Selecciona "Logout" si quieres salir de la sesión.</div>
      <div class="modal-footer">
        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
        <a class="btn btn-primary" href="/logout.php">Logout</a>
      </div>
    </div>
  </div>
</div>
</body>
</html>