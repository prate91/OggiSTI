<?php
include 'tablesFields.php';
require("functions.php");

//header('Content-Type : application/json');

if (isset($_GET['lowerDate']) && isset($_GET['upperDate'])) {
	$lowerDate = $_GET['lowerDate'];
	$upperDate = $_GET['upperDate'];
	$query = "SELECT * FROM published_events WHERE DATE_FORMAT(Date, '%m-%d') BETWEEN DATE_FORMAT('$lowerDate', '%m-%d') AND DATE_FORMAT('$upperDate', '%m-%d') ";
	#$query = "SELECT published_events.Id, published_events.Date, published_events.ItaTitle, published_events.EngTitle, published_events.Image, published_events.ImageCaption, published_events.Icon, published_events.ItaAbstract, published_events.EngAbstract, published_events.ItaDescription, published_events.EngDescription, published_events.TextReferences, published_events.Keywords, published_events.Editors, published_events.Reviser_1, published_events.Reviser_2, published_events.State, published_events.Comment, published_events.Views, published_events.Fb FROM published_events, week_events WHERE published_events.Id = week_events.Id ORDER BY Distance ASC, Rank DESC";
	// SELECT * FROM published_events WHERE DATE_FORMAT(Date, '%m-%d') BETWEEN '05-05' AND '05-12' 
	echo loadDataTables($query, $tableFieldsAllPublicated, "yes");
} else {
	echo json_encode(array("status" => "error", "details" => "parametro mancante"));
}
