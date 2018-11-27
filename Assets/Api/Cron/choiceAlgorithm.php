<?php
        

// ////////////////////////////////////////////////////////////////////////
//
// Project: OggiSTI
// Package:  API OggiSTI
// Title: Execute the algorithm to choose the event
// File: choiceAlgorithm.php
// Path: OggiSTI/Assets/Api
// Type: php
// Started: 2018.10.25
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
require_once __DIR__.'/../Config/databasesConfiguration.php';


/**
 * This query select centenary events and its multiples
 */
$query100="SELECT Id FROM today_events WHERE (DATE_FORMAT(CURDATE(), '%Y')-DATE_FORMAT(Date, '%Y'))%100 = 0";

/**
 * This query select fiftieth anniversary events and its multiples,
 * but exlude centenary events and its multiples
 */
$query50="SELECT Id FROM today_events WHERE (DATE_FORMAT(CURDATE(), '%Y')-DATE_FORMAT(Date, '%Y'))%50 = 0 AND (DATE_FORMAT(CURDATE(), '%Y')-DATE_FORMAT(Date, '%Y'))%100 != 0";

/**
 * This query select twenty-five years anniversary events and its multiples,
 * but exlude fiftieth anniversary events, centenary events and its multiples
 */
$query25="SELECT Id FROM today_events WHERE (DATE_FORMAT(CURDATE(), '%Y')-DATE_FORMAT(Date, '%Y'))%25 = 0 AND (DATE_FORMAT(CURDATE(), '%Y')-DATE_FORMAT(Date, '%Y'))%50 != 0";

/**
 * This query select ten years anniversary events and its multiples,
 * but exlude fiftieth anniversary events, centenary events and its multiples
 */
$query10="SELECT Id FROM today_events WHERE (DATE_FORMAT(CURDATE(), '%Y')-DATE_FORMAT(Date, '%Y'))%10 = 0 AND (DATE_FORMAT(CURDATE(), '%Y')-DATE_FORMAT(Date, '%Y'))%50 != 0";

/**
 * This query select all today events
 */
$queryBasic="SELECT Id FROM today_events";

/**
 * Inizialize an empty array
 */
$eventsArray = array();

/**
 * Assign basic point to events, from max (number of rows of the query)
 * to min (one).
 * Ex. if there is five events today, the query extract all events and insert them
 * in eventsArray with the corrisponding score. 
 * "event1"=>"5", "event2"=>"4", "event3"=>"3", "event4"=>"2", "event5"=>"1"
 */
$resultBasic = mysqli_query($OggiSTI_conn_adm,$queryBasic);
if (mysqli_num_rows($resultBasic) > 0) {
$pt = mysqli_num_rows($resultBasic);
while($rowBasic = mysqli_fetch_assoc($resultBasic)) {
    $eventsArray[$rowBasic["Id"]] = $pt ;
    $pt=$pt-1;
}

/**
 * Assign two hundred points for centenary events (if exists)
 * Ex. if there is five events today, the query extract all events and insert them
 * in eventsArray with the corrisponding score. 
 * "event1"=>"5", "event2"=>"4", "event3"=>"3", "event4"=>"2", "event5"=>"1"
 * if event3 is centenary id assign 200 extra-points
 * "event1"=>"5", "event2"=>"4", "event3"=>"203", "event4"=>"2", "event5"=>"1"
 */
$result100 = mysqli_query($OggiSTI_conn_adm,$query100);
if (mysqli_num_rows($result100) > 0) {
    while($row100 = mysqli_fetch_assoc($result100)) {
            $eventsArray[$row100["Id"]] += 200;
}
}

/**
 * Assign one hundred points for fitieth anniversary events (if exists)
 * Ex. if there is five events today, the query extract all events and insert them
 * in eventsArray with the corrisponding score. 
 * "event1"=>"5", "event2"=>"4", "event3"=>"3", "event4"=>"2", "event5"=>"1"
 * if event3 is centenary the algorithm assign it 200 extra-points
 * "event1"=>"5", "event2"=>"4", "event3"=>"203", "event4"=>"2", "event5"=>"1"
 * if event2 is fitieth anniversary event the algorithm assign it 100 extra-points 
 * "event1"=>"5", "event2"=>"104", "event3"=>"203", "event4"=>"2", "event5"=>"1"
 */
$result50 = mysqli_query($OggiSTI_conn_adm,$query50);
if (mysqli_num_rows($result50) > 0) {
    while($row50 = mysqli_fetch_assoc($result50)) {
            $eventsArray[$row50["Id"]] += 100;
}
}


/**
 * Assign fifty points for twenty five years anniversary events (if exists)
 * Ex. if there is five events today, the query extract all events and insert them
 * in eventsArray with the corrisponding score. 
 * "event1"=>"5", "event2"=>"4", "event3"=>"3", "event4"=>"2", "event5"=>"1"
 * if event3 is centenary the algorithm assign it 200 extra-points
 * "event1"=>"5", "event2"=>"4", "event3"=>"203", "event4"=>"2", "event5"=>"1"
 * if event2 is fitieth anniversary event the algorithm assign it 100 extra-points
 * "event1"=>"5", "event2"=>"104", "event3"=>"203", "event4"=>"2", "event5"=>"1"
 * if event4 is  twenty five years anniversary event the algorithm assign it 50 extra-points 
 * "event1"=>"5", "event2"=>"104", "event3"=>"203", "event4"=>"52", "event5"=>"1"
 */
$result25 = mysqli_query($OggiSTI_conn_adm,$query25);
    if (mysqli_num_rows($result25) > 0) {
    while($row25 = mysqli_fetch_assoc($result25)) {
            $eventsArray[$row25["Id"]] += 50;
}
}


/**
 * Assign twenty five points for ten years anniversary events (if exists)
 * Ex. if there is five events today, the query extract all events and insert them
 * in eventsArray with the corrisponding score. 
 * "event1"=>"5", "event2"=>"4", "event3"=>"3", "event4"=>"2", "event5"=>"1"
 * if event3 is centenary the algorithm assign it 200 extra-points
 * "event1"=>"5", "event2"=>"4", "event3"=>"203", "event4"=>"2", "event5"=>"1"
 * if event2 is fitieth anniversary event the algorithm assign it 100 extra-points 
 * "event1"=>"5", "event2"=>"104", "event3"=>"203", "event4"=>"2", "event5"=>"1"
 * if event4 is  twenty five years anniversary event the algorithm assign it 50 extra-points 
 * "event1"=>"5", "event2"=>"104", "event3"=>"203", "event4"=>"52", "event5"=>"1"
 * if event1 is ten years anniversary event the algorithm assign it 25 extra-points 
 * "event1"=>"30", "event2"=>"104", "event3"=>"203", "event4"=>"52", "event5"=>"1"
 */
$result10 = mysqli_query($OggiSTI_conn_adm,$query10);
$result10 = mysqli_query($OggiSTI_conn_adm,$query10);
if (mysqli_num_rows($result10) > 0) {
    while($row10 = mysqli_fetch_assoc($result10)) {
            $eventsArray[$row10["Id"]] += 25;
    }
}


/**
 * Chose the best score
 */
$todayId="";
$point=0;
foreach($eventsArray as $id => $points) {
    if($points>$point){
        $point=$points;
        $todayId=$id;
    }
}


/**
 * Insert into table today_event the best score event
 */
$toinsert="INSERT INTO today_event (Id) VALUES ('$todayId')";
$result = mysqli_query($OggiSTI_conn_adm, $toinsert);
    } else {
    echo "0 results";
}
?>
