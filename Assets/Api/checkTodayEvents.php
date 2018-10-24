<?php
	require("config.php");
   
	//header('Content-Type : application/json');
	$ok=0;

	$campi_tabella = array(
		'id_evento'
	);
	
		$query = "SELECT id_evento
		FROM eventioggi";

        $result = mysqli_query($conn,$query);
        if (mysqli_num_rows($result) > 0) {
            $ok = 1;
            echo $ok;
        }else{
            echo $ok;
        }
	
	
?>
