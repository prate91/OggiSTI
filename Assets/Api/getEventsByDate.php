<?php
include 'tablesFields.php';
require("functions.php");

//header('Content-Type : application/json');

if (isset($_GET['eventDay']) && isset($_GET['eventMonth'])) {
    $eventDay = $_GET['eventDay'];
    $eventMonth = $_GET['eventMonth'];
    $query = "SELECT * FROM published_events WHERE DAY(Date) = '$eventDay' AND MONTH(Date) = '$eventMonth'";
    echo loadDataTables($query, $tableFieldsAllPublicated, "no");
} else {
    echo json_encode(array("status" => "error", "details" => "parametro mancante"));
}
