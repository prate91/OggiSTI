<?php
// ///////////////////////////////////////////////////////////////////////////
//
// Project:   HMR OggiSTI, today in computing history
// Package:   OggiSTI generated PHP pages
// Title:     Custom initial content of session in Administrations page.
// File:      OggiSTI_sessionSet.php
// Path:      /OggiSTI/Assets/PHP/
// Type:      php
// Started:   2018.04.13
// Author(s): Nicolò Pratelli
// State:     online
//
// Version history.
// - 2018.04.22  Nicolò
//   First version
//
// ////////////////////////////////////////////////////////////////////////////
//
// This file is part of software developed by the HMR Project
// Further information at: http://progettohmr.it
// Copyright (C) 2017-2018 HMR Project.OggiSTI, G.A. Cignoni, N. Pratelli
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
// //////////////////////////////////////////////////////////////////////////
session_start();
$editor = $_SESSION['userLogin'];
$userId = $_SESSION['authId'];
$completeName = $_SESSION['completeName'];
$name =  $_SESSION['name'];
$surname =  $_SESSION['surname'];
$briefName = $_SESSION['briefName'];
$administratorPermission = $_SESSION['administratorPermission'];
$webEditorPermission = $_SESSION['webEditorPermission'];
$editorPermission = $_SESSION['editorPermission'];
$reviserPermission = $_SESSION['reviserPermission'];
?>