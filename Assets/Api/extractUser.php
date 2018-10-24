<?php

// ////////////////////////////////////////////////////////////////////////
//
// Project: OggiSTI
// Package:  API OggiSTI
// Title: Query of event
// File: extractUser
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

session_start();

require("../../../Administration/Assets/Api/funzioniUtenti.php");
$campi_tabella = array(
		'nome',
		'cognome'
);

if(isset($_GET['id_utente']))
{
	$id_utente = $_GET['id_utente'];
	$sql = "SELECT nome, cognome FROM admin WHERE id_auth='$id_utente'";
	echo carica_dati($sql, $campi_tabella);
}
else
{
	echo json_encode(array("parametro mancante"));
}

?>
