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
	
	
		$query = "SELECT t1.id_evento, data_evento, titolo_ita, titolo_eng, immagine, fonteimmagine, icona,
		abstr_ita,
		abstr_eng,
		desc_ita,
		desc_eng,
        riferimenti,
		keywords,
		redattore,
		ver_1,
		ver_2,
		stato,
        commento,
		usato FROM eventi t1 JOIN eventooggi t2 ON t1.id_evento=t2.id_evento";
		echo carica_dati_tabelle($query, $campi_tabella);
	
?>
