<?php
    

// ////////////////////////////////////////////////////////////////////////
//
// Project: OggiSTI
// Package:  API OggiSTI
// Title: Query of event
// File: countPublishedEvents.php
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
require("../../../../Config/OggiSTI_config_adm.php");


$query100="SELECT id_evento FROM eventioggi WHERE (DATE_FORMAT(CURDATE(), '%Y')-DATE_FORMAT(data_evento, '%Y'))%100 = 0";
$query50="SELECT id_evento FROM eventioggi WHERE (DATE_FORMAT(CURDATE(), '%Y')-DATE_FORMAT(data_evento, '%Y'))%50 = 0 AND (DATE_FORMAT(CURDATE(), '%Y')-DATE_FORMAT(data_evento, '%Y'))%100 != 0";
$query25="SELECT id_evento FROM eventioggi WHERE (DATE_FORMAT(CURDATE(), '%Y')-DATE_FORMAT(data_evento, '%Y'))%25 = 0 AND (DATE_FORMAT(CURDATE(), '%Y')-DATE_FORMAT(data_evento, '%Y'))%50 != 0";
$query10="SELECT id_evento FROM eventioggi WHERE (DATE_FORMAT(CURDATE(), '%Y')-DATE_FORMAT(data_evento, '%Y'))%10 = 0 AND (DATE_FORMAT(CURDATE(), '%Y')-DATE_FORMAT(data_evento, '%Y'))%50 != 0";
$queryUsato="SELECT id_evento FROM eventioggi";


$arrayEventi = array();
$resultUsato = mysqli_query($OggiSTI_conn_adm,$queryUsato);
if (mysqli_num_rows($resultUsato) > 0) {
$pt = mysqli_num_rows($resultUsato);
// output data of each row
while($rowUsato = mysqli_fetch_assoc($resultUsato)) {
    $arrayEventi[$rowUsato["id_evento"]] = $pt ;
    $pt=$pt-1;
}

//print_r($arrayEventi);
//echo "<br/>";


$result100 = mysqli_query($OggiSTI_conn_adm,$query100);
if (mysqli_num_rows($result100) > 0) {
    while($row100 = mysqli_fetch_assoc($result100)) {
            $arrayEventi[$row100["id_evento"]] += 200;
}
}

//print_r($arrayEventi);
//echo "<br/>";

$result50 = mysqli_query($OggiSTI_conn_adm,$query50);
if (mysqli_num_rows($result50) > 0) {
    while($row50 = mysqli_fetch_assoc($result50)) {
            $arrayEventi[$row50["id_evento"]] += 100;
}
}


    //print_r($arrayEventi);
// echo "<br/>";


$result25 = mysqli_query($OggiSTI_conn_adm,$query25);
    if (mysqli_num_rows($result25) > 0) {
    while($row25 = mysqli_fetch_assoc($result25)) {
            $arrayEventi[$row25["id_evento"]] += 50;
}
}


    //print_r($arrayEventi);
//echo "<br/>";


$result10 = mysqli_query($OggiSTI_conn_adm,$query10);
$result10 = mysqli_query($OggiSTI_conn_adm,$query10);
    if (mysqli_num_rows($result10) > 0) {
    while($row10 = mysqli_fetch_assoc($result10)) {
            $arrayEventi[$row10["id_evento"]] += 25;
}
}
// print_r($arrayEventi);
//echo "<br/>";

$id_evento_oggi="";
$point=0;
foreach($arrayEventi as $id => $points) {
    if($points>$point){
        $point=$points;
        $id_evento_oggi=$id;
    }
}

//echo "Id scelto: ". $id_evento_oggi . " Punti: ".$point;

$toinsert="INSERT INTO eventooggi (id_evento) VALUES ('$id_evento_oggi')";
$result = mysqli_query($OggiSTI_conn_adm, $toinsert);
    } else {
    echo "0 results";
}
?>
