<?php
include("../config.php");
$data = [];

$result = $db->rows("SELECT s.id_schedule AS id, s.id_class, s.time_start AS start, s.time_end AS end, s.day, c.name AS title, c.color AS color  
//		FROM schedule s INNER JOIN class c ON s.id_class = c.id_class ORDER BY s.id_schedule ASC");
foreach($result as $row) {
    $data[] = [
        'id'              => $row->id,
        'title'           => $row->title,
        'start'           => $row->start,
        'end'             => $row->end,
        'backgroundColor' => $row->color,
        'textColor'       => $row->color
    ];
}

echo json_encode($data);
