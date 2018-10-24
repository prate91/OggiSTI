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

function imageCount($imageName)
{
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

function deleteImg($imgPath)
{
   unlink($imgPath);
}

require("config.php");
include '../PHP/OggiSTI_sessionSet.php';
include '../PHP/OggiSTI_controlLogged.php';

// define variables and set to empty values
$var = $date = $dateCorr = $title_ita = $title_eng = $abstr_ita = $abstr_eng = $desc_ita = $desc_eng = $riferimenti = $keywords = $autore =$salvato= $stato = $verifica1 = $verifica2 = "";
$linkImg = $immagine = $inserito = $fonte_img = $textMessage = "";
$nessunaImmagine = 0;


$id_evento = isset($_POST["id_evento"]) ? $_POST['id_evento'] : '';
$var = isset($_POST["date"]) ? $_POST['date'] : '';
$date = str_replace('/', '-', $var);
$dateCorr = date('Y-m-d', strtotime($date));
$title_ita = isset($_POST["title_ita"]) ? $_POST['title_ita'] : '';
$title_eng = isset($_POST["title_eng"]) ? $_POST['title_eng'] : '';
$abstr_ita = isset($_POST["abstr_ita"]) ? $_POST['abstr_ita'] : '';
$abstr_eng = isset($_POST["abstr_eng"]) ? $_POST['abstr_eng'] : '';
$desc_ita = isset($_POST["desc_ita"]) ? $_POST['desc_ita'] : '';
$desc_eng = isset($_POST["desc_eng"]) ? $_POST['desc_eng'] : '';
$riferimenti = isset($_POST["riferimenti"]) ? $_POST['riferimenti'] : '';
$keywords = isset($_POST["keywords"]) ? $_POST['keywords'] : '';
$linkImg = isset($_POST["vecchiaImmagine"]) ? $_POST['vecchiaImmagine'] : '';
$fonte_img = isset($_POST["fonte_img"]) ? $_POST['fonte_img'] : '';
$autore = isset($_POST["autore"]) ? $_POST['autore'] : '';
$stato = isset($_POST["stato"]) ? $_POST['stato'] : '';
$salvato = isset($_POST["salvato"]) ? $_POST['salvato'] : '';
$verifica1 = isset($_POST["Iapprovazione"]) ? $_POST['Iapprovazione'] : '';
$verifica2 = isset($_POST["IIapprovazione"]) ? $_POST['IIapprovazione'] : '';

$_SESSION['id_evento'] = $id_evento;    
$_SESSION['data_evento'] = $var;
$_SESSION['titolo_ita'] = $title_ita = mysqli_real_escape_string($conn, $title_ita);
$_SESSION['titolo_eng'] = $title_eng = mysqli_real_escape_string($conn, $title_eng);
$_SESSION['abstr_ita'] = $abstr_ita = mysqli_real_escape_string($conn, $abstr_ita);
$_SESSION['abstr_eng'] = $abstr_eng = mysqli_real_escape_string($conn, $abstr_eng);
$_SESSION['desc_ita'] = $desc_ita = mysqli_real_escape_string($conn, $desc_ita);
$_SESSION['desc_eng'] = $desc_eng = mysqli_real_escape_string($conn, $desc_eng);
$_SESSION['keywords'] = $keywords = mysqli_real_escape_string($conn, $keywords);
$_SESSION['riferimenti'] = $riferimenti = mysqli_real_escape_string($conn, $riferimenti);
$_SESSION['fonte_img'] = $fonte_img = mysqli_real_escape_string($conn, $fonte_img);
$_SESSION['immagine'] = $linkImg;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // If user is going to upload an image
  if($_FILES["immagine"]["name"]!=""){

    // UPLOAD IMAGE

    // set the PATH
    $target_dir = "Img/eventi/";
    $target_file = $target_dir . basename($_FILES["immagine"]["name"]);

    // set a control variable
    $uploadOk = 1;

    // extract the extension of the image
    $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

    // if there are ten previous images
    if (imageCount($linkImg)==10) {
      // delete all the previous images
      for($i=1; $i<10; $i++){
        $tmp_img_name = imgRename($dateCorr, $id_evento, $imageFileType, $i);
        $tmp_img_name = "../" .  $target_dir . $tmp_img_name;
        deleteImg($tmp_img_name);
      }
    }
    // initialize variable of new image name
    $img_rename="";

    // if there isn't a previous image
    if($linkImg==""){
      // rename the image
      $img_rename = imgRename($dateCorr, $id_evento, $imageFileType, 0);
    }else{
      // control the number of old version and rename the image 
      $oldVersion = imageCount($linkImg);
      $img_rename = imgRename($dateCorr, $id_evento, $imageFileType, $oldVersion);
    }

    // set the PATH with new image name
    $new_loc = $target_dir . $img_rename;
    $indirizzo = "../".$new_loc;
      
    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["immagine"]["tmp_name"]);
    if($check !== false) {
        $uploadOk = 1;
    } else {
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES["immagine"]["size"] > 1048576) { // max size 1MB
        $uploadOk = 0;
    }

    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) { // only jpg, png, jpeg and gif
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        $textMessage = "Mi spiace, l'immagine non è stata caricata.";

    // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["immagine"]["tmp_name"], $indirizzo)) {
            $textMessage = "L'immagine ". basename( $_FILES["immagine"]["name"]). " è stata caricata con il nome ". $img_rename;
    		    $linkImg = $img_rename;
            $_SESSION['immagine'] = $linkImg;
        } else {
            $textMessage = "Mi space, c'è stato un errore nel caricamento della tua immagine.";
            $linkImg = "";
        }
    }
  }
  
}

// pressed invia button
if(isset($_POST['invia'])) {
   

//inserting data order
$toinsert =  "UPDATE eventiappr SET eventiappr.data_evento = '$dateCorr', eventiappr.titolo_ita ='$title_ita', eventiappr.titolo_eng = '$title_eng', eventiappr.immagine = '$linkImg', eventiappr.fonteimmagine = '$fonte_img', eventiappr.abstr_ita = '$abstr_ita', eventiappr.abstr_eng = '$abstr_eng', eventiappr.desc_ita = '$desc_ita', eventiappr.desc_eng = '$desc_eng', eventiappr.riferimenti = '$riferimenti', eventiappr.keywords = '$keywords', eventiappr.redattore = '$autore', eventiappr.ver_1 = '$verifica1', eventiappr.ver_2 = '$verifica2', eventiappr.stato = '$stato', eventiappr.salvato = '' WHERE eventiappr.id_evento = '$id_evento'";


//declare in the order variable
$result = mysqli_query($conn, $toinsert);	//order executes
if($result){

   $inserito="Inserimento avvenuto correttamente";
   $sql2 = "INSERT INTO redazione (id_evento, redattore, tipo_modifica) VALUES ('$id_evento', '$id_utente', '3')";
   mysqli_query($conn, $sql2);
   header( "Location:../PHP/OggiSTI_reviewedEvents.php?messaggio=inserito&messaggioImmagine=".$textMessage);

	

}else{

	$inserito="Inserimento non eseguito";
  header('Location:../PHP/OggiSTI_edit.php?messaggio=errore');

}

}

if(isset($_POST['salva'])) {
   // è stato premuto il secondo pulsante


//inserting data order
$toinsert =  "UPDATE eventiappr SET eventiappr.data_evento = '$dateCorr', eventiappr.titolo_ita ='$title_ita', eventiappr.titolo_eng = '$title_eng', eventiappr.immagine = '$linkImg', eventiappr.fonteimmagine = '$fonte_img', eventiappr.abstr_ita = '$abstr_ita', eventiappr.abstr_eng = '$abstr_eng', eventiappr.desc_ita = '$desc_ita', eventiappr.desc_eng = '$desc_eng', eventiappr.riferimenti = '$riferimenti', eventiappr.keywords = '$keywords', eventiappr.redattore = '$autore', eventiappr.ver_1 = '$verifica1', eventiappr.ver_2 = '$verifica2', eventiappr.stato = 'In redazione', eventiappr.salvato = '$salvato' WHERE eventiappr.id_evento = '$id_evento'";

//declare in the order variable
$result = mysqli_query($conn, $toinsert); //order 
if($result){

   $inserito="Inserimento avvenuto correttamente";
   $sql2 = "INSERT INTO redazione (id_evento, redattore, tipo_modifica) VALUES ('$id_evento', '$id_utente', '2')";
   mysqli_query($conn, $sql2);
   //$risultato = mysqli_query($conn, "SELECT MAX(id_evento) FROM eventiappr");
   //$riga = mysqli_fetch_array($risultato,MYSQLI_ASSOC);
   //$id = $riga["MAX(id_evento)"];
   header( "Location:../PHP/OggiSTI_edit.php?id_evento=$id_evento&messaggio=salva&messaggioImmagine=".$textMessage );

} else{

  $inserito="Inserimento non eseguito";
  header("Location:../PHP/OggiSTI_edit.php?id_evento=$id&messaggio=errore");

}


}

if(isset($_POST['salvaChiudi'])) {
  // è stato premuto il secondo pulsante

  if($stato=="Pubblicato"){
    $toinsert =  "UPDATE eventi SET eventi.data_evento = '$dateCorr', eventi.titolo_ita ='$title_ita', eventi.titolo_eng = '$title_eng', eventi.immagine = '$linkImg', eventi.fonteimmagine = '$fonte_img', eventi.abstr_ita = '$abstr_ita', eventi.abstr_eng = '$abstr_eng', eventi.desc_ita = '$desc_ita', eventi.desc_eng = '$desc_eng', eventi.riferimenti = '$riferimenti', eventi.keywords = '$keywords' WHERE eventi.id_evento = '$id_evento'";
  }else{
    //inserting data order
    $toinsert =  "UPDATE eventiappr SET eventiappr.data_evento = '$dateCorr', eventiappr.titolo_ita ='$title_ita', eventiappr.titolo_eng = '$title_eng', eventiappr.immagine = '$linkImg', eventiappr.fonteimmagine = '$fonte_img', eventiappr.abstr_ita = '$abstr_ita', eventiappr.abstr_eng = '$abstr_eng', eventiappr.desc_ita = '$desc_ita', eventiappr.desc_eng = '$desc_eng', eventiappr.riferimenti = '$riferimenti', eventiappr.keywords = '$keywords' WHERE eventiappr.id_evento = '$id_evento'";
  }


//declare in the order variable
$result = mysqli_query($conn, $toinsert); //order 
if($result){

  $inserito="Inserimento avvenuto correttamente";
  $sql2 = "INSERT INTO redazione (id_evento, redattore, tipo_modifica) VALUES ('$id_evento', '$id_utente', '4')";
  mysqli_query($conn, $sql2);
  //$risultato = mysqli_query($conn, "SELECT MAX(id_evento) FROM eventiappr");
  //$riga = mysqli_fetch_array($risultato,MYSQLI_ASSOC);
  //$id = $riga["MAX(id_evento)"];
  header( "Location:../PHP/OggiSTI_event.php?id_evento=$id_evento&id_state=$stato");

} else{

 $inserito="Inserimento non eseguito";
 header("Location:../PHP/OggiSTI_edit.php?id_evento=$id&messaggio=errore");

}


}

if(isset($_POST['preview'])) {
   // è stato premuto il secondo pulsante


//inserting data order
    $toinsert =  "UPDATE eventiappr SET eventiappr.data_evento = '$dateCorr', eventiappr.titolo_ita ='$title_ita', eventiappr.titolo_eng = '$title_eng', eventiappr.immagine = '$linkImg', eventiappr.fonteimmagine = '$fonte_img', eventiappr.abstr_ita = '$abstr_ita', eventiappr.abstr_eng = '$abstr_eng', eventiappr.desc_ita = '$desc_ita', eventiappr.desc_eng = '$desc_eng', eventiappr.riferimenti = '$riferimenti', eventiappr.keywords = '$keywords', eventiappr.redattore = '$autore', eventiappr.ver_1 = '$verifica1', eventiappr.ver_2 = '$verifica2', eventiappr.stato = 'In redazione', eventiappr.salvato = '$salvato' WHERE eventiappr.id_evento = '$id_evento'";

//declare in the order variable
$result = mysqli_query($conn, $toinsert); //order 
if($result){

   $inserito="Inserimento avvenuto correttamente";
   $sql2 = "INSERT INTO redazione (id_evento, redattore, tipo_modifica) VALUES ('$id_evento', '$id_utente', '2')";
   mysqli_query($conn, $sql2);
   //$risultato = mysqli_query($conn, "SELECT MAX(id_evento) FROM eventiappr");
   //$riga = mysqli_fetch_array($risultato,MYSQLI_ASSOC);
   //$id = $riga["MAX(id_evento)"];
   header( "Location:../PHP/OggiSTI_edit.php?id_evento=$id_evento&messaggio=salva&preview=ok&messaggioImmagine=".$textMessage );

} else{

  $inserito="Inserimento non eseguito";
  header("Location:../PHP/OggiSTI_edit.php?id_evento=$id&messaggio=errore");

}


}




?>

