
<?php
include 'tablesFields.php';
require("functions.php");

if (isset($_GET['eventId'])) {
    $eventId = $_GET['eventId'];
    $query = "SELECT * FROM published_events WHERE DATE_FORMAT(Date, '%m-%d')=(SELECT DATE_FORMAT(Date, '%m-%d') FROM published_events WHERE Id='$eventId') AND Id<>'$eventId'";
    echo loadDataTables($query, $tableFieldsAllPublicated, "yes");
} else {
    echo json_encode(array("status" => "error", "details" => "parametro mancante"));
}
?>