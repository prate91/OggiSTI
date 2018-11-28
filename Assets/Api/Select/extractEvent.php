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
// - 2018-02.26 Nicolò
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


require_once __DIR__.'/../Utils/tablesFields.php';
require_once __DIR__.'/../Utils/functions.php';


if(isset($_GET['eventId']))
{
	$eventId = $_GET['eventId'];
	if(isset($_GET['stateId']))
	{
		$stateId = $_GET['stateId'];
		$sql = "";
		if($stateId=="Pubblicato")
		{
			$sql = "SELECT * FROM published_events WHERE Id='$eventId'";
			echo loadDataTables($sql, $tableFieldsAllPublicated, "yes");
		}
		else
		{
			$sql = "SELECT * FROM editing_events WHERE Id = '$eventId'";
			echo loadDataTables($sql, $tableFieldsAllEditing, "yes");
		}
	}
	else
	{
		//echo json_encode(array("status" => "error", "details" => "parametro mancante"));
	}
}
else
{
	//echo json_encode(array("status" => "error", "details" => "parametro mancante"));
}


?>
