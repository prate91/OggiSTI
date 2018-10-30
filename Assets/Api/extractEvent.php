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
session_start();
$autore = $_SESSION['login_user'];
$nome_completo = $_SESSION['nome_completo'];

require("functions.php");
$campi_tabella = array(
	'id_evento',
	'data_evento',
	'titolo_ita',
	'titolo_eng',
	'immagine',
    'fonteimmagine',
	'icona',
	'abstr_ita',
	'abstr_eng',
	'desc_ita',
	'desc_eng',
    'riferimenti',
	'keywords',
	'redattore',
	'ver_1',
	'ver_2',
	'stato',
    'commento',
	'usato'
);



if(isset($_GET['id_evento']))
{
	$id_evento = $_GET['id_evento'];
	if(isset($_GET['id_state']))
	{
		$id_state = $_GET['id_state'];
		$sql = "";
		if($id_state=="Pubblicato")
		{
			$sql = "SELECT * FROM eventi WHERE id_evento='$id_evento'";
			echo load_data_tables($sql, $campi_tabella, "yes");
		}
		else
		{
			$sql = "SELECT * FROM eventiappr WHERE id_evento = '$id_evento'";
			echo load_data_tables($sql, $campi_tabella, "yes");
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
