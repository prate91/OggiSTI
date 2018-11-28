<?php

require_once __DIR__.'/../Utils/tablesFields.php';
require_once __DIR__.'/../Utils/functions.php';
	

if(isset($_GET['lowerDate'])&&isset($_GET['upperDate']))
{
	$lowerDate = $_GET['lowerDate'];
	$upperDate = $_GET['upperDate'];
	$query = "SELECT * FROM published_events WHERE DATE_FORMAT(Date, '%m-%d') BETWEEN DATE_FORMAT('$lowerDate', '%m-%d') AND DATE_FORMAT('$upperDate', '%m-%d') ";
	// SELECT * FROM published_events WHERE DATE_FORMAT(Date, '%m-%d') BETWEEN '05-05' AND '05-12' 
	echo loadDataTables($query, $tableFieldsAllPublicated, "yes");
}
else
{
	echo json_encode(array("status" => "error", "details" => "parametro mancante"));
}
?>