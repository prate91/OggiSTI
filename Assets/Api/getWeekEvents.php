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
	
	if(isset($_GET['dataInf'])&&isset($_GET['dataSup']))
	{
		$data_inf = $_GET['dataInf'];
        $data_sup = $_GET['dataSup'];
		$query = "SELECT * FROM eventi WHERE DATE_FORMAT(data_evento, '%m-%d') BETWEEN DATE_FORMAT('$data_inf', '%m-%d') AND DATE_FORMAT('$data_sup', '%m-%d') ";
       // SELECT * FROM eventi WHERE DATE_FORMAT(data_evento, '%m-%d') BETWEEN '05-05' AND '05-12' 
		echo carica_dati_tabelle($query, $campi_tabella);
	}
	else
	{
		echo json_encode(array("status" => "error", "details" => "parametro mancante"));
	}
?>