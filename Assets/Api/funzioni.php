<?php


function carica_dati($query, $campi_tabella)
{
	require("config.php");
		
			
	$risultato = array();
	$i = 0;
	$risultato_query = mysqli_query($conn, $query);
	if($risultato_query != false && mysqli_num_rows($risultato_query) > 0)
	{
		while($riga = mysqli_fetch_assoc($risultato_query))
		{
			$risultato[$i] = array();
			foreach($campi_tabella as $campo)
				$risultato [$i][$campo] = $riga[$campo]; // utf8_encode($riga[$campo])
			$i++;				
		}		
		return json_encode($risultato);
	}
	else
	{			
		return json_encode(array("status" => "error", "details" => "nessun risultato"));
	}
}

function carica_dati_tabelle($query, $campi_tabella)
{
	require("config.php");
	require("../../../Administration/Assets/Api/configUtenti.php");
	$risultato = array();
	$i = 0;
	$risultato_query = mysqli_query($conn, $query);
	
	if($risultato_query != false && mysqli_num_rows($risultato_query) > 0)
	{
		while($riga = mysqli_fetch_assoc($risultato_query))
		{
			$risultato[$i] = array();
			foreach($campi_tabella as $campo){
				if($campo=='redattore')
				{
					$autori = $riga[$campo];
					$pieces = explode(", ", $autori);
					$riga_redattori = "";
					for($j=0; $j<sizeof($pieces); $j++)
					{
						$id_utente = intval($pieces[$j]);
						$queryUtenti = "SELECT * FROM admin WHERE id_auth=$id_utente";
						$risultato_query_utenti = mysqli_query($connUtenti, $queryUtenti);
						$riga_utente = mysqli_fetch_array($risultato_query_utenti,MYSQLI_ASSOC);
						$riga_redattori =  $riga_redattori . $riga_utente["nome"] . " " . $riga_utente["cognome"]. "<br/> ";
					}
					$risultato [$i][$campo] = $riga_redattori;
				}
				else
				{
					$risultato [$i][$campo] = $riga[$campo]; // utf8_encode($riga[$campo])
				}	
			}
			$i++;		
		}		
		return json_encode($risultato);
	}
	else
	{			
		return json_encode(array("status" => "error", "details" => "nessun risultato"));
	}
}



		

?>