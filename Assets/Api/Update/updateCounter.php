<?php
	
	require_once __DIR__.'/../Config/databasesConfiguration.php';


	$OggiSTI_db = OggiSTIDBConnect();
	
	if(isset($_GET['eventId']))
	{
		$eventId = $_GET['eventId'];
        
		$query = "UPDATE published_events SET Views = Views + 1 WHERE Id='$eventId'";
		$OggiSTI_db->update($query);
	}
	else
	{
		echo json_encode(array("status" => "error", "details" => "parametro mancante"));
	}
?>
