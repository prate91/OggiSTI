<?php

// ////////////////////////////////////////////////////////////////////////
//
// Project: OggiSTI
// Package:  API OggiSTI
// Title: Query of event
// File: countRedactedEvents.php
// Path: Assets/Api
// Type: php
// Started: 2018.10.256
// Author(s): Nicolò Pratelli
// State: in use
//
// Version history.
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
include '../PHP/OggiSTI_sessionSet.php';

require("functions.php");

$tableFields = array(
	'eventsNumber'
);

/**
 * This query counts how many events have been redacted by the authenticated user
 */
$sql = "SELECT COUNT(Id) as eventsNumber FROM (SELECT Id, Editors FROM publishedEvents WHERE Editors LIKE '% $userId%' UNION SELECT Id, Editors FROM publishedEvents WHERE Editors LIKE '$userId' UNION SELECT Id, Editors FROM editingEvents WHERE Editors LIKE '% $userId%' UNION SELECT Id, Editors FROM editingEvents WHERE Editors LIKE '$userId') AS t";
echo loadDataTables($sql, $tableFields, "no");
		

?>


