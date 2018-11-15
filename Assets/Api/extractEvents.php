<?php

// ////////////////////////////////////////////////////////////////////////
//
// Project: OggiSTI
// Package:  API OggiSTI
// Title: Query of event
// File: extractEvents
// Path: asset/api
// Type: php
// Started: 2018.02.26
// Author(s): Nicolò Pratelli
// State: in use
//
// Version history.
// - 2018.02.26 Nicolò
// First version
// - 2018.10.25 Nicolò
// Added array with Fb column ancd update publicated events query with Fb column
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

require("functions.php");
include '../PHP/OggiSTI_sessionSet.php';

$tableFields = array(
		'Id',
		'ItaTitle',
		'Date',
        'Editors',
		'State',
		'Saved'
);

$publicatedTableFields = array(
	'Id',
	'ItaTitle',
	'Date',
	'Editors',
	'State',
	'Fb'
);

$allTableFiels = array(
    'Id',
    'ItaTitle',
    'Date',
    'Editors',
	'State',
	'Views',
	'Fb'
);

/**
 *  Control the state, taken from url, an execute the right query
 */
if(isset($_GET['state']))
{
	$state = $_GET['state'];
	$sql = "";
	switch($state)
	{
		case 'Salvato':
			$sql = "SELECT Id, ItaTitle, Date, Editors, State, Saved FROM editing_events WHERE Saved=$userId";
			echo loadDataTables($sql, $tableFields, "no");
			break;
		case 'Redazione':
			$sql = "SELECT Id, ItaTitle, Date, Editors, State, Saved FROM editing_events WHERE State='In redazione'";
			echo loadDataTables($sql, $tableFields, "no");
			break;
		case 'Approvazione':
			$sql = "SELECT Id, ItaTitle, Date, Editors, State, Saved FROM editing_events WHERE State='Approvazione 0/2' OR State='Approvazione 1/2'";
			echo loadDataTables($sql, $tableFields, "no");
			break;
		case 'Pubblicato':
			$sql = "SELECT Id, ItaTitle, Date, Editors, State, Fb FROM published_events";
			echo loadDataTables($sql, $publicatedTableFields, "no");
			break;
		case 'Tutti':
			$sql = "SELECT Id, ItaTitle, Date, Editors, State, Views, Fb FROM published_events UNION SELECT Id, ItaTitle, Date, Editors, State, Views, Comment FROM editing_events";
			echo loadDataTables($sql, $allTableFiels, "no");
			break;
		default:
			echo json_encode(array("tipo non riconosciuto"));
			exit(1);
	}

}
else
{
	echo json_encode(array("parametro mancante"));
}

?>
