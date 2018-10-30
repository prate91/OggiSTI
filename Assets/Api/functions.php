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
// load_data_tables that permit to choose if return html-formatted data or not
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
function clean_html($string)
{
	$spaceString = str_replace( '<', ' <', $string );
    $doubleSpace = strip_tags( $spaceString );
    $singleSpace = str_replace( '  ', ' ', $doubleSpace );
	return $singleSpace;
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
function load_data_tables($query, $fields, $formatting)
{
	require("config.php");
	require("../../../Administration/Assets/Api/configUtenti.php");
	$result = array();
	$i = 0;
	$queryResult = mysqli_query($conn, $query);
	
	if($queryResult != false && mysqli_num_rows($queryResult) > 0)
	{
		while($row = mysqli_fetch_assoc($queryResult))
		{
			$result[$i] = array();
			foreach($fields as $field){
				if($field=='redattore' | $field=='redattore_appr')
				{
					$authors = $row[$field];
					$pieces = explode(", ", $authors);
					$authorsRow = "";
					for($j=0; $j<sizeof($pieces); $j++)
					{
						$userId = intval($pieces[$j]);
						$queryUtenti = "SELECT * FROM admin WHERE id_auth=$userId";
						$userQueryResult = mysqli_query($connUtenti, $queryUtenti);
						$userRow = mysqli_fetch_array($userQueryResult,MYSQLI_ASSOC);
						$authorsRow =  $authorsRow . $userRow["nome"] . " " . $userRow["cognome"]. "<br/> ";
					}
					$result [$i][$field] = $authorsRow;
				}
				elseif ($field == 'ver_1' | $field == 'ver_2')
				{
					$userId = intval($row[$field]);
					$queryUtenti = "SELECT * FROM admin WHERE id_auth=$userId";
					$userQueryResult = mysqli_query($connUtenti, $queryUtenti);
					$userRow = mysqli_fetch_array($userQueryResult,MYSQLI_ASSOC);
					$revisore =  $userRow["nome"] . " " . $userRow["cognome"];
					$result [$i][$field] = $revisore;
				}
				else
				{	
					if($formatting=="yes")
					{
						$result [$i][$field] = $row[$field];
					}
					else
					{
						$result [$i][$field] = clean_html($row[$field]); // utf8_encode($row[$field])
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