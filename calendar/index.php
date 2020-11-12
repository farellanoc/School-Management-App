<?php
require_once('bdd.php');
include('../functions.php');


$sql = "SELECT sc.id_schedule, cl.name, cl.color, c.description, sc.day, sc.time_start, sc.time_end  FROM students s 
INNER JOIN enrollment e ON s.id = e.id_student
INNER JOIN courses c ON e.id_course = c.id_course
INNER JOIN class cl ON c.id_course = cl.id_course
INNER JOIN schedule sc ON cl.id_schedule = sc.id_schedule
WHERE s.id = ".$_SESSION["user"]["id"];

$req = $bdd->prepare($sql);
$req->execute();

$events = $req->fetchAll();

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Inicio</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- FullCalendar -->
    <link href='css/fullcalendar.css' rel='stylesheet'/>


    <!-- Custom CSS -->
    <style>
        #calendar {
            max-width: 800px;
        }

        .col-centered {
            float: none;
            margin: 0 auto;
        }
    </style>


</head>

<body>

<!-- Navigation -->
<?php if (isset($_SESSION['user'])) : ?>
    <?php
    $user = $_SESSION['user']['username'];
    $name = $_SESSION['user']['name'];
    $surname = $_SESSION['user']['surname'];
    $email = $_SESSION['user']['email'];
    ?>
    <?php include('./navbarCalendar.php'); ?>

    <!-- Page Content -->
    <div class="container">

        <div class="row">
            <div class="col-lg-12 text-center">
                <h1>AppEducativa Calendario</h1>
                <p class="lead">Aquí podrás consultar los horarios de las asignaturas donde te has inscrito</p>
                <div id="calendar" class="col-centered">
                </div>
            </div>

        </div>
        <!-- /.row -->

    </div>
    <!-- /.container -->

    <!-- jQuery Version 1.11.1 -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

    <!-- FullCalendar -->
    <script src='js/moment.min.js'></script>
    <script src='js/fullcalendar/fullcalendar.min.js'></script>
    <script src='js/fullcalendar/fullcalendar.js'></script>
    <script src='js/fullcalendar/locale/es.js'></script>


    <script>
        $(document).ready(function () {
            var date = new Date();
            var yyyy = date.getFullYear().toString();
            var mm = (date.getMonth() + 1).toString().length == 1 ? "0" + (date.getMonth() + 1).toString() : (date.getMonth() + 1).toString();
            var dd = (date.getDate()).toString().length == 1 ? "0" + (date.getDate()).toString() : (date.getDate()).toString();

            $('#calendar').fullCalendar({
                header: {
                    language: 'es',
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,basicWeek,basicDay',

                },
                defaultDate: yyyy + "-" + mm + "-" + dd,
                editable: false,
                eventLimit: true, // allow "more" link when too many events
                selectable: true,
                selectHelper: true,
                select: function (start, end) {

                    $('#ModalAdd #start').val(moment(start).format('YYYY-MM-DD HH:mm:ss'));
                    $('#ModalAdd #end').val(moment(end).format('YYYY-MM-DD HH:mm:ss'));
                    $('#ModalAdd').modal('show');
                },
                eventRender: function (event, element) {
                    element.bind('dblclick', function () {
                        $('#ModalEdit #id').val(event.id);
                        $('#ModalEdit #title').val(event.title);
                        $('#ModalEdit #color').val(event.color);
                        $('#ModalEdit').modal('show');
                    });
                },
                eventDrop: function (event, delta, revertFunc) { // si changement de position
                    edit(event);
                },
                eventResize: function (event, dayDelta, minuteDelta, revertFunc) { // si changement de longueur
                    edit(event);
                },
                events: [
                    <?php
                    $i = 0;
                    foreach($events as $event){
                    ?>
                    {
                        start: '<?php echo $event["day"]."T".$event["time_start"] ?>',
                        end: '<?php echo $event["day"]."T".$event["time_end"] ?>',
                        title: '<?php echo $event["name"]; ?>',
                        id: '<?php echo $i; ?>',
                        color: '<?php echo $event["color"]; ?>',
                    }
                    ,
                    <?php
                    $i++;
                    }
                    ?>
                ]
            })
            ;

        });

    </script>
<?php endif ?>
</body>

</html>
