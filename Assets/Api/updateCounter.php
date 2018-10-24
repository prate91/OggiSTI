<?php
			
	require("config.php");
	session_start();
    if(!isset($_SESSION['login_user'])) {
        header('Location: no_login.php?error=inv_access');
    }
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
        
		$query = "UPDATE eventi SET usato = usato + 1 WHERE id_evento='$id_evento'";
        mysqli_query($conn, $query);
	}
	else
	{
		echo json_encode(array("status" => "error", "details" => "parametro mancante"));
	}
?>
