<?php

// ////////////////////////////////////////////////////////////////////////
//
// Project: OggiSTI
// Package: OggiSTI administration
// Title: clean from empty events once a month
// File: cleanEmptyEvents.php
// Path: OggiSTI/assets/Api
// Type: php
// Started: 2018-11-16
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

require_once __DIR__ . '/../../../../../Config/DatabaseConfig.class.php';


$OggiSTI_db = DatabaseConfig::OggiSTIDBConnect();

$toDelete = "DELETE FROM editing_events WHERE Date = '0000-00-00' AND Editors = 0";
$OggiSTI_db->delete($toDelete);


?>