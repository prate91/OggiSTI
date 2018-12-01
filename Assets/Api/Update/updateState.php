<?php

// ////////////////////////////////////////////////////////////////////////
//
// Project: OggiSTI
// Package: OggiSTI administration
// Title: update state of events
// File: udateState.php
// Path: OggiSTI/assets/Api
// Type: php
// Started: 2017-03-08
// Author(s): Nicolò Pratelli
// State: in use
//
// Version history.
// - 208.11.15 Nicolò
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
$eventId = $command = $comment = $inserito =  $comm = $query = $controlIfExistQuery = "";
$err = $typeReview = 0;

if(isset($_POST['updateState'])) {
  $eventId = isset($_POST["eventId"]) ? $_POST['eventId'] : '';
  $command = isset($_POST["selectCommand"]) ? $_POST['selectCommand'] : '';  

    /**
    * Cases of Event update of state  
    */ 
    switch ($command) {
        /**
        * makeSleepy
        * Enabled only if the event is in editing state
        * Get the event and make it in a sleepy state
        */
        case 'makeSleepy':
            $toinsert = "UPDATE editing_events SET State = 'Sleepy' WHERE Id = '$eventId'";
            $typeReview = 5; // Make sleepy events
            break;
        /**
        * makeEditing
        * Enabled only if the event is in eding state and saved
        * Get the event and make it avaliable
        */
        case 'makeEditing':
            $toinsert = "UPDATE editing_events SET State = 'In redazione', Saved = 0 WHERE Id = '$eventId'";
            $typeReview = 6; // Make avaliable events
            break;
        /**
         * makeSleepyPublished
         * Enabled only if the event is published
         * Copy the event in editing state and delete it from published
         * After, move the event in sleepy state
         */
        case 'makeSleepyPublished':
            $controlIfExistQuery = "SELECT * FROM editing_events WHERE Id = '$eventId'";
            $toinsert = "INSERT INTO editing_events (Id) VALUES ('$eventId')";
            $query= "UPDATE editing_events eE, published_events pE SET eE.Date = pE.Date,  eE.ItaTitle=pE.ItaTitle, eE.EngTitle=pE.EngTitle, eE.Image=pE.Image, eE.ImageCaption=pE.ImageCaption, eE.ItaAbstract=pE.ItaAbstract, eE.EngAbstract=pE.EngAbstract, eE.ItaDescription=pE.ItaDescription, eE.EngDescription=pE.EngDescription, eE.TextReferences=pE.TextReferences, eE.Keywords=pE.Keywords, eE.Editors=pE.Editors, eE.Reviser_1='in attesa', eE.Reviser_2='in attesa', eE.State='Sleepy' WHERE eE.Id = pE.Id AND pE.Id = '$eventId'";
            $toDelete = "DELETE FROM published_events WHERE Id = '$eventId'";
            $typeReview = 5; // Make sleepy events
            break;
        /**
         * makeEditingPublished
         * Enabled only if the event is published
         * Copy the event in editing state and delete it from published
         * Make avaliable the event
         */
        case 'makeEditingPublished':
            $controlIfExistQuery = "SELECT * FROM editing_events WHERE Id = '$eventId'";
            $toinsert = "INSERT INTO editing_events (Id) VALUES ('$eventId')";
            $query= "UPDATE editing_events eE, published_events pE SET eE.Date = pE.Date,  eE.ItaTitle=pE.ItaTitle, eE.EngTitle=pE.EngTitle, eE.Image=pE.Image, eE.ImageCaption=pE.ImageCaption, eE.ItaAbstract=pE.ItaAbstract, eE.EngAbstract=pE.EngAbstract, eE.ItaDescription=pE.ItaDescription, eE.EngDescription=pE.EngDescription, eE.TextReferences=pE.TextReferences, eE.Keywords=pE.Keywords, eE.Editors=pE.Editors, eE.Reviser_1='in attesa', eE.Reviser_2='in attesa', eE.State='In redazione' WHERE eE.Id = pE.Id AND pE.Id = '$eventId'";
            $toDelete = "DELETE FROM published_events WHERE Id = '$eventId'";
            $typeReview = 6; // Make avaliable events
            break;
    }


if($controlIfExistQuery!=""){
    $resultControl =  $OggiSTI_db->select($controlIfExistQuery);
    $count = $resultControl['count']; 
    // If result matched, table row must be 1 row
    if($count == 1) {
        $resultDelete = $OggiSTI_db->delete($toDelete);
        if($resultDelete){
            $resultInsert = $OggiSTI_db->insert("INSERT INTO review (Event_Id, Reviser, Type) VALUES ('$eventId', '$userId', '$typeReview')");
            if($resultInsert){
                header( "Location:../../PHP/OggiSTI_allEvents.php?message=successState" );
            }
        }        
    }else {
        $result = $OggiSTI_db->insert($toinsert);	//order executes
        if($result){
            if($query!=""){
                $resultUpdate = $OggiSTI_db->update($query);
                if($resultUpdate){
                    $resultDelete = $OggiSTI_db->delete($toDelete);
                }
            }
            $resultInsert = $OggiSTI_db->insert("INSERT INTO review (Event_Id, Reviser, Type) VALUES ('$eventId', '$userId', '$typeReview')");
            if($resultInsert){
                header( "Location:../../PHP/OggiSTI_allEvents.php?message=successState" );
            }
        }
    }
}else{
    $result = $OggiSTI_db->insert($toinsert);	//order executes
    if($result){
        if($query!=""){
            $resultUpdate = $OggiSTI_db->update($query);
            if($resultUpdate){
                $resultDelete = $OggiSTI_db->delete($toDelete);
            }
        }
        $resultInsert = $OggiSTI_db->insert("INSERT INTO review (Event_Id, Reviser, Type) VALUES ('$eventId', '$userId', '$typeReview')");
        if($resultInsert){
            header( "Location:../../PHP/OggiSTI_allEvents.php?message=successState" );
        }
    }else{
        header( "Location:../../PHP/OggiSTI_allEvents.php?message=errore" );
    }
}
}


?>
