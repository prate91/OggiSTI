<?php
include 'tablesFields.php';
require("functions.php");

	if(isset($_GET['eventId']))
	{
		$eventId = $_GET['eventId'];
		$query = "SELECT * FROM publishedEvents WHERE Id!='$eventId' AND DAY(Date)=(SELECT DAY(Date) FROM publishedEvents WHERE Id = '$eventId') AND MONTH(Date)=(SELECT MONTH(Date) FROM publishedEvents WHERE Id = '$eventId') ORDER BY DATE_FORMAT(Date, '%Y')";
		echo loadDataTables($query, $tableFieldsAllPublicated, "yes");
	}
	else
	{
		echo json_encode(array("status" => "error", "details" => "parametro mancante"));
	}
?>
