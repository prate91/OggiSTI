<?php


// ////////////////////////////////////////////////////////////////////////
//
// Project: OggiSTI
// Package:  API Almanac OggiSTI
// Title: Query to check if there is an event today
// File: checkTodayEvents.php
// Path: OggiSTI/Assets/Api
// Type: php
// Started: 2018.10.25
// Author(s): Nicolò Pratelli
// State: in use
//
// Version history.
// - 2018.11.05 Nicolò
// Changed query with new names of tables and columns
// - 2018.10.26 Nicolò
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

require("../../../../Config/OggiSTIConfig.php");

/**
 * Variable $ok that check if there is at least one event today
 */
$ok=0;

/**
 * Execute the query,
 * if there is at least 1 row ok is setted
 */
$query = "SELECT Id FROM todayEvents";
$result = mysqli_query($conn,$query);
if (mysqli_num_rows($result) > 0) {
    $ok = 1;
    echo $ok;
}else{
    echo $ok;
}

	
?>
