<?php
function imageCount($imageName){
  $pieces = explode("_", $imageName);
  $piece = explode(".", $pieces[2]);
  $number = intval($piece[0]);
  return $number;
}

function imgRename($dateEvent, $idEvent, $imageFileType, $number) 
{
  $dateUnix = str_replace('-', '', $dateEvent);
  if($number==10){
    return $dateUnix . "_" . $idEvent . "_" . "1" . "." . $imageFileType;
  } else{
  	$number=$number+1;
    return $dateUnix . "_" . $idEvent . "_" . $number . "." . $imageFileType;
  }
}

  $string="20181212_100_10.jpg";
  echo $string;
  echo "<br/>";
  $numero=imageCount($string);
  echo $numero;
  echo "<br/>";

  

  for($i=1; $i<10; $i++){
    $tmp_img_name = imgRename("2018-12-12","100",  "jpg", $i);
    $tmp_img_name = "../Img/eventi/" . $tmp_img_name;
    echo $tmp_img_name ."<br/>";
  }

  $rename = imgRename("2018-12-12", "100", "jpg", $numero);
  echo $rename;

    echo "<br/>";
      echo "<br/>";
        echo "<br/>";

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
				if($campo=='redattore' | $campo=='redattore_appr')
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
				elseif ($campo == 'ver_1' | $campo == 'ver_2')
				{
					$id_utente = intval($riga[$campo]);
					$queryUtenti = "SELECT * FROM admin WHERE id_auth=$id_utente";
					$risultato_query_utenti = mysqli_query($connUtenti, $queryUtenti);
					$riga_utente = mysqli_fetch_array($risultato_query_utenti,MYSQLI_ASSOC);
					$revisore =  $riga_utente["nome"] . " " . $riga_utente["cognome"];
					$risultato [$i][$campo] = $revisore;
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


$campi_tabella = array(
		'id_evento',
		'titolo_ita',
		'data_evento',
        'redattore',
		'stato'
);

$campi_tabella_tutti = array(
    'id_evento_appr',
    'titolo_ita_appr',
    'data_evento_appr',
    'redattore_appr',
    'stato_appr',
    'salvato_appr',
    'id_evento',
    'titolo_ita',
    'data_evento',
    'redattore',
    'stato'
);

$query = "SELECT ea.id_evento AS id_evento_appr, ea.titolo_ita AS titolo_ita_appr, ea.data_evento AS data_evento_appr, ea.stato AS stato_appr, ea.redattore AS redattore_appr, ea.salvato AS salvato_appr, e.id_evento, e.titolo_ita, e.data_evento, e.stato, e.redattore FROM eventiappr ea LEFT JOIN eventi e ON ea.id_evento = e.id_evento UNION SELECT ea.id_evento AS id_evento_appr, ea.titolo_ita AS titolo_ita_appr, ea.data_evento AS data_evento_appr, ea.stato AS stato_appr, ea.redattore AS redattore_appr, ea.salvato AS salvato_appr, e.id_evento, e.titolo_ita, e.data_evento, e.stato, e.redattore FROM eventiappr ea RIGHT JOIN eventi e ON ea.id_evento = e.id_evento";
//echo carica_dati_tabelle($query, $campi_tabella_tutti);

$stringa = "<p>prova <br/> cavolo</p>";
echo $stringa;
$stringa2 = strip_tags($stringa);
echo $stringa2;


?>