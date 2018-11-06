<?php

// ////////////////////////////////////////////////////////////////////////
//
// Project: OggiSTI
// Package:  API OggiSTI
// Title: Get events from calendar date
// File: getCalendarDateEvent.php
// Path: OggiSTI/Assets/Api
// Type: php
// Started: 2018.06.24
// Author(s): Nicolò Pratelli
// State: in use
//
// Version history.
// - 2018.06.24 Nicolò
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

include 'tablesFields.php';
require("functions.php");

/**
 * it get a Date and takes from database the least dispayed event
 */
if(isset($_GET['eventDate']))
{
	$eventDate = $_GET['eventDate'];
	$query = "SELECT * FROM publishedEvents WHERE DAY(Date)=DAY('$eventDate') AND MONTH(Date)=MONTH('$eventDate') ORDER BY Views, DATE_FORMAT(Date, '%Y')";
	echo loadDataTables($query, $tableFieldsAllPublicated, "yes");
}
else
{
	echo json_encode(array("status" => "error", "details" => "parametro mancante"));
}
?>
