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
$id_utente = $_SESSION['id_user'];
$nome_completo = $_SESSION['nome_completo'];

require("functions.php");
$campi_tabella = array(
		'id_evento',
		'titolo_ita',
		'data_evento',
        'redattore',
		'stato'
);

$campi_tabella_pubblicati = array(
	'id_evento',
	'titolo_ita',
	'data_evento',
	'redattore',
	'stato',
	'fb'
);

$campi_tabella_tutti = array(
    'id_evento_appr',
    'titolo_ita_appr',
    'data_evento_appr',
    'redattore_appr',
    'stato_appr',
    'salvato_appr',
    'id_evento',
    'titolo_ita',
    'data_evento',
    'redattore',
    'stato'
);

if(isset($_GET['state']))
{
	$state = $_GET['state'];
	$sql = "";
	switch($state)
	{
		case 'Salvato':
			$sql = "SELECT id_evento, titolo_ita, data_evento, redattore, stato FROM eventiappr WHERE salvato=$id_utente";
			echo carica_dati_tabelle($sql, $campi_tabella);
			break;
		case 'Redazione':
			$sql = "SELECT id_evento, titolo_ita, data_evento, redattore, stato FROM eventiappr WHERE stato='In redazione'";
			echo carica_dati_tabelle($sql, $campi_tabella);
			break;
		case 'Approvazione':
			$sql = "SELECT id_evento, titolo_ita, data_evento, redattore, stato FROM eventiappr WHERE stato='Approvazione 0/2' OR stato='Approvazione 1/2'";
			echo carica_dati_tabelle($sql, $campi_tabella);
			break;
		case 'Pubblicato':
			$sql = "SELECT id_evento, titolo_ita, data_evento, redattore, stato, fb FROM eventi";
			echo carica_dati_tabelle($sql, $campi_tabella_pubblicati);
			break;
		case 'Tutti':
			$sql = "SELECT ea.id_evento AS id_evento_appr, ea.titolo_ita AS titolo_ita_appr, ea.data_evento AS data_evento_appr, ea.stato AS stato_appr, ea.redattore AS redattore_appr, ea.salvato AS salvato_appr, e.id_evento, e.titolo_ita, e.data_evento, e.stato, e.redattore FROM eventiappr ea LEFT JOIN eventi e ON ea.id_evento = e.id_evento UNION SELECT ea.id_evento AS id_evento_appr, ea.titolo_ita AS titolo_ita_appr, ea.data_evento AS data_evento_appr, ea.stato AS stato_appr, ea.redattore AS redattore_appr, ea.salvato AS salvato_appr, e.id_evento, e.titolo_ita, e.data_evento, e.stato, e.redattore FROM eventiappr ea RIGHT JOIN eventi e ON ea.id_evento = e.id_evento";
			echo carica_dati_tabelle($sql, $campi_tabella_tutti);
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
