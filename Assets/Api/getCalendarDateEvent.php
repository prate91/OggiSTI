<?php
			
	require("functions.php");
	
	//header('Content-Type : application/json');
	
	$campi_tabella = array(
		'id_evento',
		'data_evento',
		'titolo_ita',
		'titolo_eng',
		'immagine',
        'fonteimmagine',
		'icona',
		'abstr_ita',
		'abstr_eng',
		'desc_ita',
		'desc_eng',
        'riferimenti',
		'keywords',
		'redattore',
		'ver_1',
		'ver_2',
		'stato',
        'commento',
		'usato'
	);
	
	if(isset($_GET['data_evento']))
	{
		$data_evento = $_GET['data_evento'];
		$query = "SELECT * FROM eventi WHERE DAY(data_evento)=DAY('$data_evento') AND MONTH(data_evento)=MONTH('$data_evento') ORDER BY usato, DATE_FORMAT(data_evento, '%Y')";
		echo load_data_tables($query, $campi_tabella, "yes");
	}
	else
	{
		echo json_encode(array("status" => "error", "details" => "parametro mancante"));
	}
?>
