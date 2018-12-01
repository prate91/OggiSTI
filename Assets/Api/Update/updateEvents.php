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

require_once __DIR__.'/../Utils/functions.php';
require_once __DIR__.'/../../PHP/OggiSTI_sessionSet.php';
require_once __DIR__.'/../../PHP/OggiSTI_controlLogged.php';


$OggiSTI_db = DatabaseConfig::OggiSTIDBConnect();

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
$_SESSION['itaTitle'] = $itaTitle = $OggiSTI_db->escape_string($itaTitle);
$_SESSION['engTitle'] = $engTitle = $OggiSTI_db->escape_string($engTitle);
$_SESSION['itaAbstract'] = $itaAbstract = $OggiSTI_db->escape_string($itaAbstract);
$_SESSION['engAbstract'] = $engAbstract = $OggiSTI_db->escape_string($engAbstract);
$_SESSION['itaDescription'] = $itaDescription = $OggiSTI_db->escape_string($itaDescription);
$_SESSION['engDescription'] = $engDescription = $OggiSTI_db->escape_string($engDescription);
$_SESSION['keywords'] = $keywords = $OggiSTI_db->escape_string($keywords);
$_SESSION['textReferences'] = $textReferences = $OggiSTI_db->escape_string($textReferences);
$_SESSION['imageCaption'] = $imageCaption = $OggiSTI_db->escape_string($imageCaption);
$_SESSION['image'] = $imageLink;




if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $imageLink = loadImage($imageLink, $eventDateCorr, $eventId);
  
}

// pressed invia button
if(isset($_POST['invia'])) {
   

//inserting data order
$toinsert =  "UPDATE editing_events SET editing_events.Date = '$eventDateCorr', editing_events.ItaTitle ='$itaTitle', editing_events.EngTitle = '$engTitle', editing_events.Image = '$imageLink', editing_events.ImageCaption = '$imageCaption', editing_events.ItaAbstract = '$itaAbstract', editing_events.EngAbstract = '$engAbstract', editing_events.ItaDescription = '$itaDescription', editing_events.EngDescription = '$engDescription', editing_events.TextReferences = '$textReferences', editing_events.Keywords = '$keywords', editing_events.Editors = '$editors', editing_events.Reviser_1 = '$reviser1', editing_events.Reviser_2 = '$reviser2', editing_events.State = '$state' WHERE editing_events.Id = '$eventId'";


//declare in the order variable
$result = $OggiSTI_db->insert($toinsert);	//order executes
if($result){
    $resultInsert = $OggiSTI_db->insert("INSERT INTO editing (Event_Id, Editor, Type) VALUES ('$eventId', '$userId', '3')");
    if($resultInsert){
      header( "Location:../../PHP/OggiSTI_reviewedEvents.php?message=inserito&messageImmagine=".$textMessage);
    }
}else{
  header('Location:../../PHP/OggiSTI_edit.php?message=errore');

}

}

if(isset($_POST['salva'])) {
   // è state premuto il secondo pulsante

//inserting data order
$toinsert =  "UPDATE editing_events SET editing_events.Date = '$eventDateCorr', editing_events.ItaTitle ='$itaTitle', editing_events.EngTitle = '$engTitle', editing_events.Image = '$imageLink', editing_events.ImageCaption = '$imageCaption', editing_events.ItaAbstract = '$itaAbstract', editing_events.EngAbstract = '$engAbstract', editing_events.ItaDescription = '$itaDescription', editing_events.EngDescription = '$engDescription', editing_events.TextReferences = '$textReferences', editing_events.Keywords = '$keywords', editing_events.Editors = '$editors', editing_events.Reviser_1 = '$reviser1', editing_events.Reviser_2 = '$reviser2', editing_events.State = 'In redazione', editing_events.Saved = '$saved' WHERE editing_events.Id = '$eventId'";

//declare in the order variable
$result = $OggiSTI_db->insert($toinsert);	//order executes
if($result){
  $resultInsert = $OggiSTI_db->insert("INSERT INTO editing (Event_Id, Editor, Type) VALUES ('$eventId', '$userId', '2')");
    if($resultInsert){
    header( "Location:../../PHP/OggiSTI_edit.php?eventId=$eventId&message=salva&messageImmagine=".$textMessage );
    }
} else{
    header("Location:../../PHP/OggiSTI_edit.php?eventId=$eventId&message=errore");
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
$result = $OggiSTI_db->insert($toinsert);	//order executes
if($result){
  $resultInsert = $OggiSTI_db->insert("INSERT INTO editing (Event_Id, Editor, Type) VALUES ('$eventId', '$userId', '4')");
    if($resultInsert){
     header( "Location:../../PHP/OggiSTI_event.php?eventId=$eventId&stateId=$state");
    }
} else{
 $inserito="Inserimento non eseguito";
 header("Location:../../PHP/OggiSTI_edit.php?eventId=$eventId&message=errore");

}


}

if(isset($_POST['preview'])) {
   // è state premuto il secondo pulsante


//inserting data order
    $toinsert =  "UPDATE editing_events SET editing_events.Date = '$eventDateCorr', editing_events.ItaTitle ='$itaTitle', editing_events.EngTitle = '$engTitle', editing_events.Image = '$imageLink', editing_events.ImageCaption = '$imageCaption', editing_events.ItaAbstract = '$itaAbstract', editing_events.EngAbstract = '$engAbstract', editing_events.ItaDescription = '$itaDescription', editing_events.EngDescription = '$engDescription', editing_events.TextReferences = '$textReferences', editing_events.Keywords = '$keywords', editing_events.Editors = '$editors', editing_events.Reviser_1 = '$reviser1', editing_events.Reviser_2 = '$reviser2', editing_events.State = 'In redazione', editing_events.Saved = '$saved' WHERE editing_events.Id = '$eventId'";

//declare in the order variable
$result = $OggiSTI_db->insert($toinsert);	//order executes
if($result){
  $resultInsert = $OggiSTI_db->insert("INSERT INTO editing (Event_Id, Editor, Type) VALUES ('$eventId', '$userId', '2')");
    if($resultInsert){
       header( "Location:../../PHP/OggiSTI_edit.php?eventId=$eventId&message=salva&preview=ok&messageImmagine=".$textMessage );
    }
} else{
  $inserito="Inserimento non eseguito";
  header("Location:../../PHP/OggiSTI_edit.php?eventId=$eventId&message=errore");
}


}




?>

