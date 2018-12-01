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

require_once __DIR__.'/../Utils/functions.php';
require_once __DIR__.'/../../PHP/OggiSTI_sessionSet.php';
require_once __DIR__.'/../../PHP/OggiSTI_controlLogged.php';

$OggiSTI_db = DatabaseConfig::OggiSTIDBConnect();

// define variables and set to empty values
$eventId = $state = $comment = $inserito =  $comm = "";
$err = 0;

if(isset($_POST['facebookOn'])) {
  $eventId = isset($_POST["eventId"]) ? $_POST['eventId'] : '';
  $toinsert = "UPDATE published_events SET Fb = 1 WHERE Id = '$eventId'";
  $result = $OggiSTI_db->insert($toinsert);	//order executes
  if($result){
      $resultInsert = $OggiSTI_db->insert("INSERT INTO review (Event_Id, Reviser, Type) VALUES ('$eventId', '$userId', '3')");
      if($resultInsert){
        header( "Location:../../PHP/OggiSTI_event.php?eventId=$eventId&stateId=Pubblicato" );
      }
  }
}

if(isset($_POST['facebookOff'])) {
  $eventId = isset($_POST["eventId"]) ? $_POST['eventId'] : '';
  $toinsert = "UPDATE published_events SET Fb = 0 WHERE Id = '$eventId'";
  $result = $OggiSTI_db->insert($toinsert);	//order executes
  if($result){
      $resultInsert = $OggiSTI_db->insert("INSERT INTO review (Event_Id, Reviser, Type) VALUES ('$eventId', '$userId', '4')");
      if($resultInsert){
        header( "Location:../../PHP/OggiSTI_event.php?eventId=$eventId&stateId=Pubblicato" );
      }
  }
}

if(isset($_POST['redazione'])) {
   // è stato premuto il primo pulsante
    
  $state = 'In redazione';
  $eventId = isset($_POST["eventId"]) ? $_POST['eventId'] : '';
  $comment = isset($_POST["comment"]) ? $_POST['comment'] : '';
  $reviser1 = isset($_POST["reviser1"]) ? $_POST['reviser1'] : '';
  $reviser2 = isset($_POST["reviser2"]) ? $_POST['reviser2'] : '';
    
  $comment = $OggiSTI_db->escape_string($comment); 
 
//inserting data order
$toinsert = "UPDATE editing_events SET State = '$state', Comment = '$comment' WHERE Id = '$eventId'";

//declare in the order variable
$result = $OggiSTI_db->insert($toinsert);	//order executes
if($result){
  $resultInsert = $OggiSTI_db->insert("INSERT INTO review (Event_Id, Reviser, Type, Comment) VALUES ('$eventId', '$userId', '2', '$comment')");
  if($resultInsert){
    header( "Location:../../PHP/OggiSTI_redactionEvents.php?message=redazione" );
  }
}else{
  header( "Location:../../PHP/OggiSTI_redactionEvents.php?message=errore" );
}

}

if(isset($_POST['approva'])) {
   // è stato premuto il secondo pulsante
  $eventId = isset($_POST["eventId"]) ? $_POST['eventId'] : '';
  $reviser1 = isset($_POST["reviser1"]) ? $_POST['reviser1'] : '';
  $reviser2 = isset($_POST["reviser2"]) ? $_POST['reviser2'] : '';
  $comment = isset($_POST["comment"]) ? $_POST['comment'] : '';
    
  $comment = $OggiSTI_db->escape_string($comment); 
  
  

  if($reviser1==0 && $reviser2==0 ){ // Case 1: event without review
    $state = "Approvazione 1/2";
    $reviser1 = $userId;
    $comm = "Mandato in attesa della II approvazione";
    $toUpdate = "UPDATE editing_events SET Reviser_1 = '$reviser1', Reviser_2 = '$reviser2', State = '$state',  Comment = '$comment' WHERE Id = '$eventId'";
    $result = $OggiSTI_db->update($toUpdate);	//order executes
    if($result){
      $resultInsert = $OggiSTI_db->insert("INSERT INTO review (Event_Id, Reviser, Type, Comment) VALUES ('$eventId', '$userId', '1', '$comment')");
      header( "Location:../../PHP/OggiSTI_reviewedEvents.php?message=approvato");
    }else{
      header( "Location:../../PHP/OggiSTI_reviewedEvents.php?message=errore" );
    }
  }else if($reviser1!=0 && $reviser2==0 ){ // Case 2: event with first review
    if($reviser1==$userId){
        header( "Location:../../PHP/OggiSTI_reviewedEvents.php?message=erroreappr" );
    }else{
      $state = "Pubblicato";
      $reviser2 = $userId;
      $comm = "Pubblicato";
      $toinsert = "INSERT INTO published_events (Id, Reviser_1, Reviser_2, State, Comment) 
      VALUES ('$eventId','$reviser1','$reviser2','$state','$comment') ON DUPLICATE KEY UPDATE Reviser_1='$reviser1',Reviser_2='$reviser2',State='$state',Comment='$comment'";
      $toUpdate= "UPDATE published_events pE, editing_events eE SET pE.Date = eE.Date,  pE.ItaTitle=eE.ItaTitle, pE.EngTitle=eE.EngTitle, pE.Image=eE.Image, pE.ImageCaption=eE.ImageCaption, pE.ItaAbstract=eE.ItaAbstract, pE.EngAbstract=eE.EngAbstract, pE.ItaDescription=eE.ItaDescription, pE.EngDescription=eE.EngDescription, pE.TextReferences=eE.TextReferences, pE.Keywords=eE.Keywords, pE.Editors=eE.Editors WHERE pE.Id = eE.Id AND eE.Id = '$eventId'";
      $toDelete="DELETE FROM editing_events WHERE Id='$eventId'";
      $result = $OggiSTI_db->insert($toinsert);	//order executes
      if($result){
        $OggiSTI_db->update($toUpdate);
        $OggiSTI_db->delete($toDelete);
        $OggiSTI_db->insert("INSERT INTO review (Event_Id, Reviser, Type, Comment) VALUES ('$eventId', '$userId', '1', '$comment')");
        header( "Location:../../PHP/OggiSTI_publishedEvents.php?message=approvato");
      }else{
        header( "Location:../../PHP/OggiSTI_publishedEvents.php?message=errore" );
      }
    }
  }
  
}

if(isset($_POST['redazionePubblicato'])) {
   // è stato premuto il primo pulsante


  $eventId = isset($_POST["eventId"]) ? $_POST['eventId'] : '';
  $state = 'In redazione';
  $comment = isset($_POST["comment"]) ? $_POST['comment'] : '';

  $comment = $OggiSTI_db->escape_string($comment); 
 
$toinsert = "INSERT INTO editing_events (Id, State, Comment) VALUES ('$eventId','$state','$comment')";
$toUpdate= "UPDATE editing_events pE, published_events eE SET pE.Date = eE.Date,  pE.ItaTitle=eE.ItaTitle, pE.EngTitle=eE.EngTitle, pE.Image=eE.Image, pE.ImageCaption=eE.ImageCaption, pE.ItaAbstract=eE.ItaAbstract, pE.EngAbstract=eE.EngAbstract, pE.ItaDescription=eE.ItaDescription, pE.EngDescription=eE.EngDescription, pE.TextReferences=eE.TextReferences, pE.Keywords=eE.Keywords, pE.Editors=eE.Editors, pE.Reviser_1='in attesa', pE.Reviser_2='in attesa' WHERE pE.Id = eE.Id AND eE.Id = '$eventId'";

//declare in the order variable
$result = $OggiSTI_db->insert($toinsert);	//order executes
if($result){
   $resultUpdate = $OggiSTI_db->update($toUpdate);
   if($resultUpdate){
      $OggiSTI_db->insert("INSERT INTO review (Event_Id, Reviser, Type, Comment) VALUES ('$eventId', '$userId', '2', '$comment')");	
      header( "Location:../../PHP/OggiSTI_redactionEvents.php?message=redazione" );
   }
}else{
  header( "Location:../../PHP/OggiSTI_publishedEvents.php?message=errore" );
}


}
?>
