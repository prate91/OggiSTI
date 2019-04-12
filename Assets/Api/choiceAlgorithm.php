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



$tDate = strtotime("now");
$todayDate = date('Y-m-d', $tDate);
$uDate = strtotime("+3 days");
$upperDate = date('Y-m-d', $uDate);
$lDate = strtotime("-3 days");
$lowerDate = date('Y-m-d', $lDate);


$truncateTodayEvent = "TRUNCATE TABLE today_event"; // delete from next update
$truncateTodayEvents = "TRUNCATE TABLE today_events";
$truncateWeekEvents = "TRUNCATE TABLE week_events";

$extractTodayEvents = "INSERT INTO today_events SELECT Id, Date, Views, Views, Fb FROM published_events WHERE DAY(Date)=DAY(CURRENT_DATE) and MONTH(Date)=MONTH(CURRENT_DATE) ORDER BY Views";


$extractWeekEvent = "INSERT INTO week_events SELECT Id, Date, Views, Views, Views FROM published_events WHERE DATE_FORMAT(Date, '%m-%d') <> DATE_FORMAT(' $todayDate', '%m-%d') AND (DATE_FORMAT(Date, '%m-%d') BETWEEN DATE_FORMAT('$lowerDate', '%m-%d') AND DATE_FORMAT('$upperDate', '%m-%d')) ORDER BY Views ";


$result = mysqli_query($OggiSTI_conn_adm, $truncateTodayEvent);
if ($result) {
    echo "Truncate table today events";
    echo "<br/>";
}
$result = mysqli_query($OggiSTI_conn_adm, $truncateTodayEvents);
if ($result) {
    echo "Truncate table today event";
    echo "<br/>";
}
$result = mysqli_query($OggiSTI_conn_adm, $truncateWeekEvents);
if ($result) {
    echo "Truncate table week events";
    echo "<br/>";
}

$result = mysqli_query($OggiSTI_conn_adm, $extractTodayEvents);
if ($result) {
    echo "Insert into today events";
    echo "<br/>";
}
$result = mysqli_query($OggiSTI_conn_adm, $extractWeekEvent);
if ($result) {
    echo "Insert into week events";
    echo "<br/>";
}

$todayEventsArrayRank = rank($OggiSTI_conn_adm, "today_events");
$weekEventsArrayRank = rank($OggiSTI_conn_adm, "week_events");
$todayEventsArrayDistance = distance($OggiSTI_conn_adm, "week_events");

updateRank($OggiSTI_conn_adm, "today_events", $todayEventsArrayRank);
updateRank($OggiSTI_conn_adm, "week_events", $weekEventsArrayRank);
updateDistance($OggiSTI_conn_adm, "week_events", $todayEventsArrayDistance);


function distance($conn, $table)
{

    $eventsArray = array();


    $queryBasic = "SELECT Id, Date FROM  $table";

    $resultBasic = mysqli_query($conn, $queryBasic);
    if (mysqli_num_rows($resultBasic) > 0) {
        while ($rowBasic = mysqli_fetch_assoc($resultBasic)) {
            $dist = 0;
            $date = strval(date('d-m', strtotime($rowBasic["Date"])));
            $tomorrow = strval(date('d-m', strtotime("+1 days")));
            $afterTomorrow = strval(date('d-m', strtotime("+2 days")));
            $dayAfterTomorrow = strval(date('d-m', strtotime("+3 days")));
            $yesterday = strval(date('d-m', strtotime("-1 days")));
            $afterYesterday = strval(date('d-m', strtotime("-2 days")));
            $dayAfterYesterday = strval(date('d-m', strtotime("-3 days")));
            switch ($date) {
                case $tomorrow:
                    $dist = 1;
                    break;
                case $afterTomorrow:
                    $dist = 2;
                    break;
                case $dayAfterTomorrow:
                    $dist = 3;
                    break;
                case $yesterday:
                    $dist = 4;
                    break;
                case $afterYesterday:
                    $dist = 5;
                    break;
                case $dayAfterYesterday:
                    $dist = 6;
                    break;
                default:
                    $dist = 0;
            }
            $eventsArray[$rowBasic["Id"]] = $dist;
        }
    }
    return $eventsArray;
}


/**
 * Establishes a rank among the events by assigning more score to the centenary, fifty and ten year events
 *
 * @param [type] $conn database connection
 * @param [type] $table tble on which to rank
 * @return void
 */
function rank($conn, $table)
{


    /**
     * This query select centenary events and its multiples
     */
    $query100 = "SELECT Id FROM $table WHERE (DATE_FORMAT(CURDATE(), '%Y')-DATE_FORMAT(Date, '%Y'))%100 = 0";

    /**
     * This query select fiftieth anniversary events and its multiples,
     * but exlude centenary events and its multiples
     */
    $query50 = "SELECT Id FROM  $table WHERE (DATE_FORMAT(CURDATE(), '%Y')-DATE_FORMAT(Date, '%Y'))%50 = 0 AND (DATE_FORMAT(CURDATE(), '%Y')-DATE_FORMAT(Date, '%Y'))%100 != 0";

    /**
     * This query select twenty-five years anniversary events and its multiples,
     * but exlude fiftieth anniversary events, centenary events and its multiples
     */
    $query25 = "SELECT Id FROM  $table WHERE (DATE_FORMAT(CURDATE(), '%Y')-DATE_FORMAT(Date, '%Y'))%25 = 0 AND (DATE_FORMAT(CURDATE(), '%Y')-DATE_FORMAT(Date, '%Y'))%50 != 0";

    /**
     * This query select ten years anniversary events and its multiples,
     * but exlude fiftieth anniversary events, centenary events and its multiples
     */
    $query10 = "SELECT Id FROM  $table WHERE (DATE_FORMAT(CURDATE(), '%Y')-DATE_FORMAT(Date, '%Y'))%10 = 0 AND (DATE_FORMAT(CURDATE(), '%Y')-DATE_FORMAT(Date, '%Y'))%50 != 0";

    /**
     * This query select all today events
     */
    $queryBasic = "SELECT Id FROM  $table";

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
    $resultBasic = mysqli_query($conn, $queryBasic);
    if (mysqli_num_rows($resultBasic) > 0) {
        $pt = mysqli_num_rows($resultBasic);
        while ($rowBasic = mysqli_fetch_assoc($resultBasic)) {
            $eventsArray[$rowBasic["Id"]] = $pt;
            $pt = $pt - 1;
        }

        /**
         * Assign two hundred points for centenary events (if exists)
         * Ex. if there is five events today, the query extract all events and insert them
         * in eventsArray with the corrisponding score. 
         * "event1"=>"5", "event2"=>"4", "event3"=>"3", "event4"=>"2", "event5"=>"1"
         * if event3 is centenary id assign 200 extra-points
         * "event1"=>"5", "event2"=>"4", "event3"=>"203", "event4"=>"2", "event5"=>"1"
         */
        $result100 = mysqli_query($conn, $query100);
        if (mysqli_num_rows($result100) > 0) {
            while ($row100 = mysqli_fetch_assoc($result100)) {
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
        $result50 = mysqli_query($conn, $query50);
        if (mysqli_num_rows($result50) > 0) {
            while ($row50 = mysqli_fetch_assoc($result50)) {
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
        $result25 = mysqli_query($conn, $query25);
        if (mysqli_num_rows($result25) > 0) {
            while ($row25 = mysqli_fetch_assoc($result25)) {
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

        $result10 = mysqli_query($conn, $query10);
        if (mysqli_num_rows($result10) > 0) {
            while ($row10 = mysqli_fetch_assoc($result10)) {
                $eventsArray[$row10["Id"]] += 25;
            }
        }

        return $eventsArray;
    }
}

/**
 * Chose the best score
 */
$todayId = "";
$point = 0;
foreach ($todayEventsArrayRank as $id => $points) {
    if ($points > $point) {
        $point = $points;
        $todayId = $id;
    }
}


function updateRank($conn, $table, $eventsArray)
{
    /**
     * Update table today_events with the rank
     */
    foreach ($eventsArray as $id => $points) {
        echo $id . "=>" . $points . "<br/>";
        $toUpdate = "UPDATE $table SET Rank='$points' WHERE Id='$id'";
        $result = mysqli_query($conn, $toUpdate);
    }
}

function updateDistance($conn, $table, $eventsArray)
{
    /**
     * Update table today_events with the rank
     */
    foreach ($eventsArray as $id => $points) {
        echo $id . "=>" . $points . "<br/>";
        $toUpdate = "UPDATE $table SET Distance='$points' WHERE Id='$id'";
        $result = mysqli_query($conn, $toUpdate);
    }
}


$queryBasic = "SELECT Id FROM  $table";

$resultBasic = mysqli_query($conn, $queryBasic);
if (mysqli_num_rows($resultBasic) > 0) {
    /**
     * Insert into table today_event the best score event
     */
    $toinsert = "INSERT INTO today_event (Id) VALUES ('$todayId')";
    $result = mysqli_query($OggiSTI_conn_adm, $toinsert);
} else {
    echo "0 results";
}
