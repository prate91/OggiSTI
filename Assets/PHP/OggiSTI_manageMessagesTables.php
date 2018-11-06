<?php
// ///////////////////////////////////////////////////////////////////////////
//
// Project:   HMR OggiSTI, today in computing history
// Package:   OggiSTI generated PHP pages
// Title:     Custom initial content of the messages in tables pages in Administrations page.
// File:      OggiSTI_manageMessagesTables.php
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

$message = $mess = $errore = $notizia = "";
if(isset($_GET["message"])){
    $mess=$_GET["message"];
    if($mess=="redazione"){
        $notizia='<div class="alert alert-success" id="alertEvento">
        <button type="button" class="close" data-dismiss="alert">x</button>
        <p>Evento mandato in redazione</p></div>';
    }
    if($mess=="approvato"){
       $notizia='<div class="alert alert-success" id="alertEvento">
    <button type="button" class="close" data-dismiss="alert">x</button>
    <p>Evento approvato da '.$completeName.'</p></div>';
    }
    if($mess=="errore"){
        $notizia='<div class="alert alert-danger" id="alertEvento">
    <button type="button" class="close" data-dismiss="alert">x</button>
    <p>Errore</p></div>';
    }
    if($mess=="erroreappr"){
        $notizia='<div class="alert alert-danger" id="alertEvento">
    <button type="button" class="close" data-dismiss="alert">x</button>
    <p>La stessa persona non può approvare un evento due volte</p></div>';
    }
     if($mess=="eliminato"){
        $notizia='<div class="alert alert-success" id="alertEvento">
    <button type="button" class="close" data-dismiss="alert">x</button>
    <p>Evento eliminato</p></div>';
    }
}
?>