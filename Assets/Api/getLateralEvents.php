<?php
			
	require("functions.php");
	
	//header('Content-Type : application/json');
	
	$campi_tabella = array(
		'id_evento',
		'data_evento',
		'titolo_ita',
		'titolo_eng',
		'immagine',
		'icona',
		'abstr_ita',
		'abstr_eng',
		'desc_ita',
		'desc_eng',
		'keywords',
		'redattore',
		'ver_1',
		'ver_2',
		'stato',
        'commento',
		'usato'
	);
	
	if(isset($_GET['id_evento']))
	{
		$id_evento = $_GET['id_evento'];
		$query = "SELECT * FROM eventi WHERE id_evento!='$id_evento' AND DAY(data_evento)=(SELECT DAY(data_evento) FROM eventi WHERE id_evento = '$id_evento') AND MONTH(data_evento)=(SELECT MONTH(data_evento) FROM eventi WHERE id_evento = '$id_evento') ORDER BY DATE_FORMAT(data_evento, '%Y')";
		echo carica_dati_tabelle($query, $campi_tabella);
	}
	else
	{
		echo json_encode(array("status" => "error", "details" => "parametro mancante"));
	}
?>
