<?php
include 'tablesFields.php';
require("functions.php");

if (isset($_GET['eventId'])) {
    $eventId = $_GET['eventId'];
    $query = "SELECT * FROM published_events WHERE Id='$eventId'";
    echo loadDataTables($query, $tableFieldsAllPublicated, "yes");
} else {
    echo json_encode(array("status" => "error", "details" => "parametro mancante"));
}
