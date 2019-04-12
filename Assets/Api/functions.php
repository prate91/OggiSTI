<?php

// ////////////////////////////////////////////////////////////////////////
//
// Project: OggiSTI
// Package:  API OggiSTI
// Title: function
// File: functions.php
// Path: Assets/Api
// Type: php
// Started: 2018.06.24
// Author(s): Nicolò Pratelli
// State: in use
//
// Version history.
// - 2018.06.24 Nicolò
// First version
// - 2018.10.30 Nicolò
// Inserted clean html function. Inserted variable formatting in the function
// loadDataTables that permit to choose if return html-formatted data or not
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


 /**
  * Clean a string from html tags. It introduces the space if there isn't.
  *
  * @author Nicolò Pratelli
  *
  * @since 2.0
  *
  * @param string $string  the string to be cleaned
  */
function cleanHTML($string)
{
	$spaceString = str_replace( '<', ' <', $string );
    $doubleSpace = strip_tags( $spaceString );
    $singleSpace = str_replace( '  ', ' ', $doubleSpace );
	return $singleSpace;
}

 /**
  * Load brief name from database EPICAC, table people.
  *
  * @author Nicolò Pratelli
  *
  * @since 2.0
  *
  * @param string $idUser  id of the user that have to be linked to table people
  */
function loadBriefName($idUser)
{
	require("../../../../Config/EPICAC_config_rd.php");
	if($idUser==0){
		return 0;
	}
	else{
		$userDataQuery = "SELECT * FROM people WHERE IdPp=$idUser";
		$userDataQueryResult = mysqli_query($EPICAC_conn_rd, $userDataQuery);
		$userDataRow = mysqli_fetch_array($userDataQueryResult,MYSQLI_ASSOC);
		return $userDataRow["Brief"];
	}
}

/**
  * Load complete name, composed by name and surname, from database EPICAC, table people.
  *
  * @author Nicolò Pratelli
  *
  * @since 2.0
  *
  * @param string $idUser  id of the user that have to be linked to table people
  */
  function loadCompletefName($idUser)
  {
	require("../../../../Config/EPICAC_config_rd.php");
	if($idUser==0){
		return 0;
	}
	else{
		$userDataQuery = "SELECT * FROM people WHERE IdPp=$idUser";
		$userDataQueryResult = mysqli_query($EPICAC_conn_rd, $userDataQuery);
		$userDataRow = mysqli_fetch_array($userDataQueryResult,MYSQLI_ASSOC);
		return $userDataRow["Name"] . " " . $userDataRow["Surname"];
	}
  }

/**
  * Load link id from table admin.
  *
  * @author Nicolò Pratelli
  *
  * @since 2.0
  *
  * @param string $idUser  id of the user
  */
function loadPeopleId($idUser)
{
	require("../../../../Config/Users_config_adm.php");
	$userQuery = "SELECT * FROM admin WHERE AuthId=$idUser";
	$userQueryResult = mysqli_query($users_conn_adm, $userQuery);
	$userRow = mysqli_fetch_array($userQueryResult,MYSQLI_ASSOC);
	return $userRow["IdPp_Id"];
}



/**
  * Load and build editing chronology of every event.
  *
  * @author Nicolò Pratelli
  *
  * @since 3.0
  *
  * @param string $eventId id of the event
  */
function loadEditingChronology($eventId)
{
	$editingsList = "";
	require("../../../../Config/OggiSTI_config_adm.php");
	$queryEditing = "SELECT * FROM editing WHERE Event_Id='$eventId'";
	$resultEditing = mysqli_query($OggiSTI_conn_adm, $queryEditing);
	while ($rowEditing = mysqli_fetch_assoc($resultEditing)) {
		switch ($rowEditing["Type"]) {
			case 1:
				$type="creato";
				break;
			case 2:
				$type="salvato";
				break;
			case 3:
				$type="inviato in approvazione";
				break;
			case 4:
				$type="modifica rapida";
		}
		$editingsList = $editingsList . "<li>" . $rowEditing["EditDate"] . " - " .loadBriefName(loadPeopleId($rowEditing["Editor"])) .  " - ".  $type ."</li>";
			}
		return $editingsList;
}

/**
  * Load and build review chronology of every event.
  *
  * @author Nicolò Pratelli
  *
  * @since 3.0
  *
  * @param string $eventId id of the event
  */
function loadReviewChronology($eventId)
{
	$reviewsList = "";
	require("../../../../Config/OggiSTI_config_adm.php");
	$queryReview = "SELECT * FROM review WHERE Event_Id='$eventId'";
	$resurlReview = mysqli_query($OggiSTI_conn_adm, $queryReview);
	while ($rowReview = mysqli_fetch_assoc($resurlReview)) {
		switch ($rowReview["Type"]) {
			case 1:
				$type="approvato";
				break;
			case 2:
				$type="inviato in redazione";
				break;
			case 3:
				$type="pubblicabile su Facebook";
				break;
			case 4:
				$type="non pubblicabile su Facebook";
				break;
			case 5:
				$type="reso dormiente";
				break;
			case 6:
				$type="reso disponibile";
				break;
		}
	$reviewsList = $reviewsList . "<li>" . $rowReview["ReviewDate"] . " - " . loadBriefName(loadPeopleId($rowReview["Reviser"])) . " - ".  $type ."</li>";
		}
		return $reviewsList;
}

 /**
  * Function that execute the query and return json encoded result
  *
  * @author Nicolò Pratelli
  *
  * @since 1.0
  *
  * @param string $query  the query to be executed
  * @param array $fields ontains the fields of the query
  * @param string $formatting it permitt to choose yes or no if you want the html formatted field or not
  */
function loadDataTables($query, $fields, $formatting)
{
	// require("config.php");
	require("../../../../Config/OggiSTI_config_adm.php");
	$result = array();
	$i = 0;
	$queryResult = mysqli_query($OggiSTI_conn_adm, $query);
	
	if($queryResult != false && mysqli_num_rows($queryResult) > 0)
	{
		while($row = mysqli_fetch_assoc($queryResult))
		{
			$result[$i] = array();
			foreach($fields as $field){
				if($field=='Editors')
				{
					$authors = $row[$field];
					$pieces = explode(", ", $authors);
					$authorsRow = "";
					for($j=0; $j<sizeof($pieces); $j++)
					{
						$userId = intval($pieces[$j]);
						$authorsRow =  $authorsRow . loadBriefName(loadPeopleId($userId)) . "<br/> ";
					}
					$result [$i][$field] = $authorsRow;
				}
				elseif ($field == 'Reviser_1' | $field == 'Reviser_2')
				{
					$userId = intval($row[$field]);
					$result [$i][$field] = loadBriefName(loadPeopleId($userId));
				}
				else
				{	
					if($formatting=="yes")
					{
						$result [$i][$field] = $row[$field];
					}
					else
					{
						$result [$i][$field] = cleanHTML($row[$field]); // utf8_encode($row[$field])
					}
				}	
			}
			$i++;		
		}		
		return json_encode($result);
	}
	else
	{			
		return json_encode(array("status" => "error", "details" => "no result"));
	}
}



		

?>