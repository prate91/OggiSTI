<?php

// ////////////////////////////////////////////////////////////////////////
//
// Project: OggiSTI
// Package: OggiSTI administration
// Title: upeventDates
// File: upeventDates.php
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

function imgRename($eventDate, $eventId, $imageFileType, $number) 
{
  $unixDate = str_replace('-', '', $eventDate);
  if($number==10){
    return $unixDate . "_" . $eventId . "_" . "1" . "." . $imageFileType;
  } else{
    $number=$number+1;
    return $unixDate . "_" . $eventId . "_" . $number . "." . $imageFileType;
  }
}

function deleteImg($imgPath)
{
   unlink($imgPath);
}

//require("config.php");
require("../../../../Config/OggiSTIConfig.php");
include '../PHP/OggiSTI_sessionSet.php';
include '../PHP/OggiSTI_controlLogged.php';

// define variables and set to empty values
$var = $eventDate = $eventDateCorr = $itaTitle = $engTitle = $itaAbstract = $engAbstract = $itaDescription = $engDescription = $textReferences = $keywords = $editors =$saved= $state = $reviser1 = $reviser2 = "";
$imageLink = $image = $inserito = $imageCaption = $textMessage = "";
$nessunaImmagine = 0;


$eventId = isset($_POST["eventId"]) ? $_POST['eventId'] : '';
$var = isset($_POST["eventDate"]) ? $_POST['eventDate'] : '';
$eventDate = str_replace('/', '-', $var);
$eventDateCorr = date('Y-m-d', strtotime($eventDate));
$itaTitle = isset($_POST["itaTitle"]) ? $_POST['itaTitle'] : '';
$engTitle = isset($_POST["engTitle"]) ? $_POST['engTitle'] : '';
$itaAbstract = isset($_POST["itaAbstract"]) ? $_POST['itaAbstract'] : '';
$engAbstract = isset($_POST["engAbstract"]) ? $_POST['engAbstract'] : '';
$itaDescription = isset($_POST["itaDescription"]) ? $_POST['itaDescription'] : '';
$engDescription = isset($_POST["engDescription"]) ? $_POST['engDescription'] : '';
$textReferences = isset($_POST["textReferences"]) ? $_POST['textReferences'] : '';
$keywords = isset($_POST["keywords"]) ? $_POST['keywords'] : '';
$imageLink = isset($_POST["oldImage"]) ? $_POST['oldImage'] : '';
$imageCaption = isset($_POST["imageCaption"]) ? $_POST['imageCaption'] : '';
$editors = isset($_POST["editors"]) ? $_POST['editors'] : '';
$state = isset($_POST["state"]) ? $_POST['state'] : '';
$saved = isset($_POST["saved"]) ? $_POST['saved'] : '';
$reviser1 = isset($_POST["IApprovation"]) ? $_POST['IApprovation'] : '';
$reviser2 = isset($_POST["IIApprovation"]) ? $_POST['IIApprovation'] : '';

$_SESSION['eventId'] = $eventId;    
$_SESSION['eventDate'] = $var;
$_SESSION['itaTitle'] = $itaTitle = mysqli_real_escape_string($conn, $itaTitle);
$_SESSION['engTitle'] = $engTitle = mysqli_real_escape_string($conn, $engTitle);
$_SESSION['itaAbstract'] = $itaAbstract = mysqli_real_escape_string($conn, $itaAbstract);
$_SESSION['engAbstract'] = $engAbstract = mysqli_real_escape_string($conn, $engAbstract);
$_SESSION['itaDescription'] = $itaDescription = mysqli_real_escape_string($conn, $itaDescription);
$_SESSION['engDescription'] = $engDescription = mysqli_real_escape_string($conn, $engDescription);
$_SESSION['keywords'] = $keywords = mysqli_real_escape_string($conn, $keywords);
$_SESSION['textReferences'] = $textReferences = mysqli_real_escape_string($conn, $textReferences);
$_SESSION['imageCaption'] = $imageCaption = mysqli_real_escape_string($conn, $imageCaption);
$_SESSION['image'] = $imageLink;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // If user is going to upload an image
  if($_FILES["image"]["name"]!=""){

    // UPLOAD IMAGE

    // set the PATH
    $target_dir = "Img/eventi/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);

    // set a control variable
    $uploadOk = 1;

    // extract the extension of the image
    $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

    // if there are ten previous images
    if (imageCount($imageLink)==10) {
      // delete all the previous images
      for($i=1; $i<10; $i++){
        $tmp_img_name = imgRename($eventDateCorr, $eventId, $imageFileType, $i);
        $tmp_img_name = "../" .  $target_dir . $tmp_img_name;
        deleteImg($tmp_img_name);
      }
    }
    // initialize variable of new image name
    $imgRename="";

    // if there isn't a previous image
    if($imageLink==""){
      // rename the image
      $imgRename = imgRename($eventDateCorr, $eventId, $imageFileType, 0);
    }else{
      // control the number of old version and rename the image 
      $oldVersion = imageCount($imageLink);
      $imgRename = imgRename($eventDateCorr, $eventId, $imageFileType, $oldVersion);
    }

    // set the PATH with new image name
    $new_loc = $target_dir . $imgRename;
    $indirizzo = "../".$new_loc;
      
    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if($check !== false) {
        $uploadOk = 1;
    } else {
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES["image"]["size"] > 1048576) { // max size 1MB
        $uploadOk = 0;
    }

    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) { // only jpg, png, jpeg and gif
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        $textMessage = "Mi spiace, l'image non è stata caricata.";

    // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $indirizzo)) {
            $textMessage = "L'image ". basename( $_FILES["image"]["name"]). " è stata caricata con il nome ". $imgRename;
    		    $imageLink = $imgRename;
            $_SESSION['image'] = $imageLink;
        } else {
            $textMessage = "Mi space, c'è state un errore nel caricamento della tua image.";
            $imageLink = "";
        }
    }
  }
  
}

// pressed invia button
if(isset($_POST['invia'])) {
   

//inserting data order
$toinsert =  "UPDATE editing_events SET editing_events.Date = '$eventDateCorr', editing_events.ItaTitle ='$itaTitle', editing_events.EngTitle = '$engTitle', editing_events.Image = '$imageLink', editing_events.ImageCaption = '$imageCaption', editing_events.ItaAbstract = '$itaAbstract', editing_events.EngAbstract = '$engAbstract', editing_events.ItaDescription = '$itaDescription', editing_events.EngDescription = '$engDescription', editing_events.TextReferences = '$textReferences', editing_events.Keywords = '$keywords', editing_events.Editors = '$editors', editing_events.Reviser_1 = '$reviser1', editing_events.Reviser_2 = '$reviser2', editing_events.State = '$state' WHERE editing_events.Id = '$eventId'";


//declare in the order variable
$result = mysqli_query($conn, $toinsert);	//order executes
if($result){

   $inserito="Inserimento avvenuto correttamente";
   $sql2 = "INSERT INTO editing (Event_Id, Editor, Type) VALUES ('$eventId', '$userId', '3')";
   mysqli_query($conn, $sql2);
   header( "Location:../PHP/OggiSTI_reviewedEvents.php?message=inserito&messageImmagine=".$textMessage);

	

}else{

	$inserito="Inserimento non eseguito";
  header('Location:../PHP/OggiSTI_edit.php?message=errore');

}

}

if(isset($_POST['salva'])) {
   // è state premuto il secondo pulsante


//inserting data order
$toinsert =  "UPDATE editing_events SET editing_events.Date = '$eventDateCorr', editing_events.ItaTitle ='$itaTitle', editing_events.EngTitle = '$engTitle', editing_events.Image = '$imageLink', editing_events.ImageCaption = '$imageCaption', editing_events.ItaAbstract = '$itaAbstract', editing_events.EngAbstract = '$engAbstract', editing_events.ItaDescription = '$itaDescription', editing_events.EngDescription = '$engDescription', editing_events.TextReferences = '$textReferences', editing_events.Keywords = '$keywords', editing_events.Editors = '$editors', editing_events.Reviser_1 = '$reviser1', editing_events.Reviser_2 = '$reviser2', editing_events.State = 'In redazione', editing_events.Saved = '$saved' WHERE editing_events.Id = '$eventId'";

//declare in the order variable
$result = mysqli_query($conn, $toinsert); //order 
if($result){

   $inserito="Inserimento avvenuto correttamente";
   $sql2 = "INSERT INTO editing (Event_Id, Editor, Type) VALUES ('$eventId', '$userId', '2')";
   mysqli_query($conn, $sql2);
   //$risultato = mysqli_query($conn, "SELECT MAX(eventId) FROM editing_events");
   //$riga = mysqli_fetch_array($risultato,MYSQLI_ASSOC);
   //$id = $riga["MAX(eventId)"];
   header( "Location:../PHP/OggiSTI_edit.php?eventId=$eventId&message=salva&messageImmagine=".$textMessage );

} else{

  $inserito="Inserimento non eseguito";
  header("Location:../PHP/OggiSTI_edit.php?eventId=$eventId&message=errore");

}


}

if(isset($_POST['salvaChiudi'])) {
  // è state premuto il secondo pulsante

  if($state=="Pubblicato"){
    $toinsert =  "UPDATE published_events SET published_events.Date = '$eventDateCorr', published_events.ItaTitle ='$itaTitle', published_events.EngTitle = '$engTitle', published_events.Image = '$imageLink', published_events.ImageCaption = '$imageCaption', published_events.ItaAbstract = '$itaAbstract', published_events.EngAbstract = '$engAbstract', published_events.ItaDescription = '$itaDescription', published_events.EngDescription = '$engDescription', published_events.TextReferences = '$textReferences', published_events.Keywords = '$keywords' WHERE published_events.Id = '$eventId'";
  }else{
    //inserting data order
    $toinsert =  "UPDATE editing_events SET editing_events.Date = '$eventDateCorr', editing_events.ItaTitle ='$itaTitle', editing_events.EngTitle = '$engTitle', editing_events.Image = '$imageLink', editing_events.ImageCaption = '$imageCaption', editing_events.ItaAbstract = '$itaAbstract', editing_events.EngAbstract = '$engAbstract', editing_events.ItaDescription = '$itaDescription', editing_events.EngDescription = '$engDescription', editing_events.TextReferences = '$textReferences', editing_events.Keywords = '$keywords' WHERE editing_events.Id = '$eventId'";
  }


//declare in the order variable
$result = mysqli_query($conn, $toinsert); //order 
if($result){

  $inserito="Inserimento avvenuto correttamente";
  $sql2 = "INSERT INTO editing (eventId, Editors, Type) VALUES ('$eventId', '$userId', '4')";
  mysqli_query($conn, $sql2);
  //$risultato = mysqli_query($conn, "SELECT MAX(eventId) FROM editing_events");
  //$riga = mysqli_fetch_array($risultato,MYSQLI_ASSOC);
  //$id = $riga["MAX(eventId)"];
  header( "Location:../PHP/OggiSTI_event.php?eventId=$eventId&stateId=$state");

} else{

 $inserito="Inserimento non eseguito";
 header("Location:../PHP/OggiSTI_edit.php?eventId=$eventId&message=errore");

}


}

if(isset($_POST['preview'])) {
   // è state premuto il secondo pulsante


//inserting data order
    $toinsert =  "UPDATE editing_events SET editing_events.Date = '$eventDateCorr', editing_events.ItaTitle ='$itaTitle', editing_events.EngTitle = '$engTitle', editing_events.Image = '$imageLink', editing_events.ImageCaption = '$imageCaption', editing_events.ItaAbstract = '$itaAbstract', editing_events.EngAbstract = '$engAbstract', editing_events.ItaDescription = '$itaDescription', editing_events.EngDescription = '$engDescription', editing_events.TextReferences = '$textReferences', editing_events.Keywords = '$keywords', editing_events.Editors = '$editors', editing_events.Reviser_1 = '$reviser1', editing_events.Reviser_2 = '$reviser2', editing_events.State = 'In redazione', editing_events.Saved = '$saved' WHERE editing_events.Id = '$eventId'";

//declare in the order variable
$result = mysqli_query($conn, $toinsert); //order 
if($result){

   $inserito="Inserimento avvenuto correttamente";
   $sql2 = "INSERT INTO editing (Event_Id, Editor, Type) VALUES ('$eventId', '$userId', '2')";
   mysqli_query($conn, $sql2);
   //$risultato = mysqli_query($conn, "SELECT MAX(eventId) FROM editing_events");
   //$riga = mysqli_fetch_array($risultato,MYSQLI_ASSOC);
   //$id = $riga["MAX(eventId)"];
   header( "Location:../PHP/OggiSTI_edit.php?eventId=$eventId&message=salva&preview=ok&messageImmagine=".$textMessage );

} else{

  $inserito="Inserimento non eseguito";
  header("Location:../PHP/OggiSTI_edit.php?eventId=$eventId&message=errore");

}


}




?>

