<?php
	
	require("../../../../Config/OggiSTIConfig.php");

	//header('Content-Type : application/json');
	
	if(isset($_GET['eventId']))
	{
		$eventId = $_GET['eventId'];
        
		$query = "UPDATE published_events SET Views = Views + 1 WHERE Id='$eventId'";
        mysqli_query($conn, $query);
	}
	else
	{
		echo json_encode(array("status" => "error", "details" => "parametro mancante"));
	}
?>
