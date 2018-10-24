<?php

// ////////////////////////////////////////////////////////////////////////
//
// Project: OggiSTI
// Package: OggiSTI administration
// Title: updateEvents
// File: updateEvents.php
// Path: OggiSTI/assets/api
// Type: php
// Started: 2017-03-08
// Author(s): Nicolò Pratelli
// State: in use
//
// Version history.
// - 2018.03.05 Nicolò
// Changed upload image.
// New type of rename: yyyymmdd_idEvent.imageFileExtension
// Delete old image
// - 2017.03.08 Nicolò
// First version
//
// ////////////////////////////////////////////////////////////////////////
//
// This file is part of software developed by the HMR Project
// Further information at: http://progettohmr.it
// Copyright (C) 2017 HMR Project, Nicolò Pratelli
//
// This is free software; you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published
// by the Free Software Foundation; either version 3.0 of the License,
// or (at your option) any later version.
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty
// of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
// See the GNU General Public License for more details.
// You should have received a copy of the GNU General Public License
// along with this program; if not, see <http://www.gnu.org/licenses/>.
//
// ////////////////////////////////////////////////////////////////////////

require("config.php");
include '../PHP/OggiSTI_sessionSet.php';
include '../PHP/OggiSTI_controlLogged.php';

// define variables and set to empty values
$id_evento = $stato = $commento = $inserito =  $comm = "";
$err = 0;

if(isset($_POST['facebookOn'])) {
  $id_evento = isset($_POST["id_evento"]) ? $_POST['id_evento'] : '';
  $toinsert = "UPDATE eventi SET fb = 1 WHERE id_evento = '$id_evento'";
  $result = mysqli_query($conn, $toinsert); //order executes
  if($result){
    $sql2 = "INSERT INTO revisione (id_evento, revisore, tipo_revisione) VALUES ('$id_evento', '$id_utente', '3')";
    mysqli_query($conn, $sql2);
    header( "Location:../PHP/OggiSTI_event.php?id_evento=$id_evento&id_state=Pubblicato" );
  }
}

if(isset($_POST['facebookOff'])) {
  $id_evento = isset($_POST["id_evento"]) ? $_POST['id_evento'] : '';
  $toinsert = "UPDATE eventi SET fb = 0 WHERE id_evento = '$id_evento'";
  $result = mysqli_query($conn, $toinsert); //order executes
  if($result){
    $sql2 = "INSERT INTO revisione (id_evento, revisore, tipo_revisione) VALUES ('$id_evento', '$id_utente', '4')";
    mysqli_query($conn, $sql2);
    header( "Location:../PHP/OggiSTI_event.php?id_evento=$id_evento&id_state=Pubblicato" );
  }
}

if(isset($_POST['redazione'])) {
   // è stato premuto il primo pulsante
    
  $stato = 'In redazione';
  $id_evento = isset($_POST["id_evento"]) ? $_POST['id_evento'] : '';
  $commento = isset($_POST["commento"]) ? $_POST['commento'] : '';
  $ver_1 = isset($_POST["ver_1"]) ? $_POST['ver_1'] : '';
  $ver_2 = isset($_POST["ver_2"]) ? $_POST['ver_2'] : '';
    
  $commento = mysqli_real_escape_string($conn, $commento);
  
 
//inserting data order
$toinsert = "UPDATE eventiappr SET stato = '$stato', commento = '$commento' WHERE id_evento = '$id_evento'";

//declare in the order variable
$result = mysqli_query($conn, $toinsert);	//order executes
if($result){
   $inserito="Commento inserito correttamente ed evento mandato in redazione";
   $sql2 = "INSERT INTO revisione (id_evento, revisore, tipo_revisione, commento) VALUES ('$id_evento', '$id_utente', '2', '$commento')";
   mysqli_query($conn, $sql2);
   header( "Location:../PHP/OggiSTI_redactionEvents.php?messaggio=redazione" );
}else{
	$inserito="Inserimento non eseguito";
  header( "Location:../PHP/OggiSTI_redactionEvents.php?messaggio=errore" );
}

}

if(isset($_POST['approva'])) {
   // è stato premuto il secondo pulsante


  $id_evento = isset($_POST["id_evento"]) ? $_POST['id_evento'] : '';
  $ver_1 = isset($_POST["ver_1"]) ? $_POST['ver_1'] : '';
  $ver_2 = isset($_POST["ver_2"]) ? $_POST['ver_2'] : '';
  $commento = isset($_POST["commento"]) ? $_POST['commento'] : '';
    
  $commento = mysqli_real_escape_string($conn, $commento);
    
  if($ver_1==0 && $ver_2==0 ){
      $stato = "Approvazione 1/2";
      $ver_1 = $id_utente;
      $comm = "Mandato in attesa della II approvazione";
      $toinsert = "UPDATE eventiappr SET ver_1 = '$ver_1', ver_2 = '$ver_2', stato = '$stato',  commento = '$commento' WHERE id_evento = '$id_evento'";
      $result = mysqli_query($conn, $toinsert); //order
      if($result){
         $sql2 = "INSERT INTO revisione (id_evento, revisore, tipo_revisione, commento) VALUES ('$id_evento', '$id_utente', '1', '$commento')";
         mysqli_query($conn, $sql2);
     }
  }else{
      if($ver_1==$id_utente){
         $err=1;
      }else{
      $stato = "Pubblicato";
      $ver_2 = $id_utente;
      $comm = "Pubblicato";
      $toinsert = "INSERT INTO eventi (id_evento, ver_1, ver_2,  stato, commento) 
      VALUES ('$id_evento','$ver_1','$ver_2','$stato','$commento') ON DUPLICATE KEY UPDATE ver_1='$ver_1',ver_2='$ver_2',stato='$stato',commento='$commento'";
      $query= "UPDATE eventi t1, eventiappr t2 SET t1.data_evento = t2.data_evento,  t1.titolo_ita=t2.titolo_ita, t1.titolo_eng=t2.titolo_eng, t1.immagine=t2.immagine, t1.fonteimmagine=t2.fonteimmagine, t1.abstr_ita=t2.abstr_ita, t1.abstr_eng=t2.abstr_eng, t1.desc_ita=t2.desc_ita, t1.desc_eng=t2.desc_eng, t1.riferimenti=t2.riferimenti, t1.keywords=t2.keywords, t1.redattore=t2.redattore WHERE t1.id_evento = t2.id_evento AND t2.id_evento = '$id_evento'";
      $query2="DELETE FROM eventiappr WHERE id_evento='$id_evento'";
      $result = mysqli_query($conn, $toinsert); //order
      mysqli_query($conn, $query);
      mysqli_query($conn, $query2);
      if($result){
         $sql2 = "INSERT INTO revisione (id_evento, revisore, tipo_revisione, commento) VALUES ('$id_evento', '$id_utente', '1', '$commento')";
         mysqli_query($conn, $sql2);
     }
      }
  }
  
  


//declare in the order variable
if($err==0){
$result = mysqli_query($conn, $toinsert); //order 
if($result){
   $inserito="Commento inserito correttamente ed evento $comm";
   header( "Location:../PHP/OggiSTI_publicatedEvents.php?messaggio=approvato");

} else{
 $inserito="Inserimento non eseguito";
 header( "Location:../PHP/OggiSTI_publicatedEvents.php?messaggio=errore" );

}
}else{
    header( "Location:../PHP/OggiSTI_reviewedEvents.php?messaggio=erroreappr" );
}



}

if(isset($_POST['redazionePubblicato'])) {
   // è stato premuto il primo pulsante


  $id_evento = isset($_POST["id_evento"]) ? $_POST['id_evento'] : '';
  $stato = 'In redazione';
  $commento = isset($_POST["commento"]) ? $_POST['commento'] : '';
  $commento = mysqli_real_escape_string($conn, $commento);
 
$toinsert = "INSERT INTO eventiappr (id_evento, stato, commento) VALUES ('$id_evento','$stato','$commento')";
$query= "UPDATE eventiappr t1, eventi t2 SET t1.data_evento = t2.data_evento,  t1.titolo_ita=t2.titolo_ita, t1.titolo_eng=t2.titolo_eng, t1.immagine=t2.immagine, t1.fonteimmagine=t2.fonteimmagine, t1.abstr_ita=t2.abstr_ita, t1.abstr_eng=t2.abstr_eng, t1.desc_ita=t2.desc_ita, t1.desc_eng=t2.desc_eng, t1.riferimenti=t2.riferimenti, t1.keywords=t2.keywords, t1.redattore=t2.redattore, t1.ver_1='in attesa', t1.ver_2='in attesa' WHERE t1.id_evento = t2.id_evento AND t2.id_evento = '$id_evento'";

//declare in the order variable
$result = mysqli_query($conn, $toinsert);	//order executes
if($result){
   $inserito="Inserimento avvenuto correttamente";
   mysqli_query($conn, $query);
   $sql2 = "INSERT INTO revisione (id_evento, revisore, tipo_revisione, commento) VALUES ('$id_evento', '$id_utente', '2', '$commento')";
  mysqli_query($conn, $sql2);
  header( "Location:../PHP/OggiSTI_redactionEvents.php?messaggio=redazione" );
	
}else{
	$inserito="Inserimento non eseguito";
 header( "Location:../PHP/OggiSTI_publicatedEvents.php?messaggio=errore" );
}


}
?>
