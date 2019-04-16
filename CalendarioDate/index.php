<?php
///////////////////////////////////////////////////////////////////////////
//
// Project:   HMR OggiSTI, today in computing history
// Package:   Web documentation
// Title:     OggiSTI - Calendario
// File:      index.php
// Path:      /OggiSTI/CalcolaPunteggio
// Type:      php
// Started:   2017.03.08
// Author(s): Nicolò Pratelli
// State:     online
//
// Version history.
// - 2017.03.08  Nicolò
//   First version
// - 2018.03.14 Nicolò
//   Added OggiSTI_Style.css
// - 2018.10.27 Nicolò
//   Added a list of all publicated events
// - 2018.10.28 Nicolò
//   Solved no space between words after html tags removal
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
// ///////////////////////////////////////////////////////////////////////

require("../../../Config/OggiSTI_config_adm.php");

// from yyyy-mm-dd to mm-dd(yyyy)
function formatDatemmddyyyy($dateStr)
{
    $dArr = explode("-", $dateStr);
    return $dArr[1] . "-" . $dArr[2] . " (" . $dArr[0] . ")";
}


function calcolaOrdinaleGiorno($giorno, $mese)
{
    $ordinale = 0;
    switch ($mese) {
        case 1:
            return $ordinale = $giorno - 1;
        case 2:
            return $ordinale = 31 + $giorno - 1;
        case 3:
            return $ordinale = 60 + $giorno - 1;
        case 4:
            return $ordinale = 91 + $giorno - 1;
        case 5:
            return $ordinale = 121 + $giorno - 1;
        case 6:
            return $ordinale = 152 + $giorno - 1;
        case 7:
            return $ordinale = 182 + $giorno - 1;
        case 8:
            return $ordinale = 213 + $giorno - 1;
        case 9:
            return $ordinale = 244 + $giorno - 1;
        case 10:
            return $ordinale = 274 + $giorno - 1;
        case 11:
            return $ordinale = 305 + $giorno - 1;
        case 12:
            return $ordinale = 335 + $giorno - 1;
    }
}


function calcolaGiornoDaOrdinale($ordinale)
{
    $ordinale = $ordinale + 1;
    if ($ordinale >= 1 && $ordinale <= 31) {
        return $ordinale . "/1";
    }
    if ($ordinale >= 32 && $ordinale <= 60) {
        $ordinale = $ordinale - 31;
        return $ordinale . "/2";
    }
    if ($ordinale >= 61 && $ordinale <= 91) {
        $ordinale = $ordinale - 60;
        return $ordinale . "/3";
    }
    if ($ordinale >= 92 && $ordinale <= 121) {
        $ordinale = $ordinale - 91;
        return $ordinale . "/4";
    }
    if ($ordinale >= 122 && $ordinale <= 152) {
        $ordinale = $ordinale - 121;
        return $ordinale . "/5";
    }
    if ($ordinale >= 153 && $ordinale <= 182) {
        $ordinale = $ordinale - 152;
        return $ordinale . "/6";
    }
    if ($ordinale >= 183 && $ordinale <= 213) {
        $ordinale = $ordinale - 182;
        return $ordinale . "/7";
    }
    if ($ordinale >= 214 && $ordinale <= 244) {
        $ordinale = $ordinale - 213;
        return $ordinale . "/8";
    }
    if ($ordinale >= 245 && $ordinale <= 274) {
        $ordinale = $ordinale - 244;
        return $ordinale . "/9";
    }
    if ($ordinale >= 275 && $ordinale <= 305) {
        $ordinale = $ordinale - 274;
        return $ordinale . "/10";
    }
    if ($ordinale >= 306 && $ordinale <= 335) {
        $ordinale = $ordinale - 305;
        return $ordinale . "/11";
    }
    if ($ordinale >= 336 && $ordinale <= 366) {
        $ordinale = $ordinale - 335;
        return $ordinale . "/12";
    }
}

// Funzione che calcola la distanza dalla data più vicina inferiore
function cercaInferiore($giorniAnno, $data)
{
    $i = 1;
    while ($i < 10) {
        if ($data + 1 - $i == 0) {
            $data = $data + 366;
        }
        if ($giorniAnno[$data - $i] == 1 || $giorniAnno[$data - $i] == 2) {
            return $i;
        }
        $i++;
    }
    return $i;
}
// Funzione che calcola la distanza dalla data più vicina superiore
function cercaSuperiore($giorniAnno, $data)
{
    $i = 1;
    while ($i < 10) {
        if ($data - 1 + $i == 365) {
            $data = $data - 366;
        }
        if ($giorniAnno[$data + $i] == 1 || $giorniAnno[$data + $i] == 2) {
            return $i;
        }
        $i++;
    }
    return $i;
}

$giorniAnno = array();
for ($i = 0; $i <= 365; $i++) {
    $giorniAnno[] = 0;
}


$arrayDate = array();

$sql = "SELECT DISTINCT DAY(Date) as giorno, MONTH(Date) as mese FROM published_events";
$result = mysqli_query($OggiSTI_conn_adm, $sql);
while ($row = mysqli_fetch_row($result)) {
    $query = "SELECT COUNT(*) FROM published_events WHERE DAY(Date)= '$row[0]' AND MONTH(Date)= '$row[1]'";
    $result2 = mysqli_query($OggiSTI_conn_adm, $query);
    $row2 = mysqli_fetch_row($result2);
    $giorniAnno[calcolaOrdinaleGiorno($row[0], $row[1])] = $row2[0];
    array_push($arrayDate, calcolaOrdinaleGiorno($row[0], $row[1]));

    //echo 'Giorno: '. $row[0] ." - Mese: ". $row[1]. "<br/>";
    //echo "arrayDate: ";
    //print_r($arrayDate);
}


// $max = sizeof($arrayDate);
// for ($i = 0; $i <= 365; $i++) {
//     for ($j = 0; $j < $max; $j++) {
//         if ($i == $arrayDate[$j]) {
//             $giorniAnno[$i] = 1;
//         }
//     }
// }


?>


<!DOCTYPE html>
<html lang='it'>

<head>
    <meta charset="UTF-8">
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-111997111-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'UA-111997111-1');
    </script>

    <title>Calendario - Oggi nella storia dell'informatica - HMR</title>

    <!-- Load OggiSTI standard libraries -->
    <link rel='stylesheet' href='../../Assets/Libs/Bootstrap/CSS/bootstrap.css'>
    <link rel='stylesheet' href='../../Assets/Libs/jQuery-UI/jquery-ui.css'>
    <link rel='stylesheet' href='../../Assets/Libs/jQuery-UI/jquery-ui.theme.css'>
    <link rel='stylesheet' href='../../Assets/Libs/jQuery-UI/jquery-ui.structure.css'>

    <script src='../../Assets/Libs/jQuery/jquery-3.3.1.min.js'></script>
    <script src='../../Assets/Libs/jQuery-UI/jquery-ui.js'></script>
    <script src='../Assets/JS/datepicker-it.js'></script>
    <script src='../../Assets/Libs/Bootstrap/JS/bootstrap.js'></script>
    <script src="https://www.w3schools.com/lib/w3.js"></script>
    <script src='../Assets/JS/OggiSTI_calendar.js'></script>


    <!-- Load HMR CSS styles & fonts -->
    <link rel="stylesheet" type="text/css" href="../../HMR_Style.css">

    <!-- Load OggiSTI CSS styles & fonts -->
    <link rel="stylesheet" type="text/css" href="../Assets/CSS/OggiSTI_Style.css">

    <!-- Load favorite icon -->
    <link rel="icon" type="image/png" href="../Assets/Img/logo-oggiSTI16x16.png" />

    <!-- Load HMR standard libraries -->
    <script type='text/javascript' src='../../EPICAC/JSwebsite/searchAndSharing.js'></script>
    <script type='text/javascript' src='../../Assets/JS/HMR_CreaHTML.js'></script>

    <!-- Load OggiSTI standard Javascript -->
    <script src='../Assets/JS/OggiSTI_function.js'></script>

    <!-- Load OggiSTI Almanac Javascript -->
    <script src='../Assets/JS/OggiSTI_almanac.js'></script>

    <meta name="description" content="HMR.OggiSTI" />

    <meta name="keywords" content="hackerando hacker hacking macchina ridotta calcolatrice elettronica pisana CEP electronic computer storia history informatica computer science archeologia archeology sperimentale experimental ricostruzioni rebuilding replica repliche replicas simulazione simulation simulatori simulators macchine passato past machines 
                    documenti documents cignoni giovanni pratelli nicolò oggi almanacco oggisti" />


</head>

<body>

    <!-- Standard HMRWeb header ///////////////////////////////////////////////////
                    // For banner:
                    // - set level, 1 = "../", 2 = "../../" and so on;
                    // - set image, file name and extension, no path, has to be in /Assets/Images.
                    // For menu:
                    // - set level, same as banner;
                    // - set active menu entry, 1=Cronologia, 2=Eventi and so on.  -->
    <div class="HMR_Banner">
        <script>
            creaHeader(2, 'HMR_2017g_GC-WebHeaderRite-270x105-3.png')
        </script>
    </div>
    <div id="HMR_Menu" class="HMR_Menu">
        <script>
            creaMenu(2, 5)
        </script>
    </div>


    <span class="stop"></span>

    <!-- Actual page content starts here ///////////////////////////////////////-->
    <div class="HMR_Content">

        <h1 class="stop">Cerca un evento</h1>
        <div id="oggiSTI_dataPicker">
            <input class="form-control" id="oggiSTI_picker" name="oggiSTI_picker" type='text' />
        </div>

        <div id="OggiSTI_calendarEventsQuery"></div>

        <h1 class="stop">Calendario degli eventi pubblicati</h1>
        <?php
        echo '<table id="oggiSTI_visualizza_eventi">';
        echo '<tr><td>&nbsp;&nbsp;&nbsp;</td>';
        echo '<td>&nbsp;1 </td>';
        echo '<td>&nbsp;2 </td>';
        echo '<td>&nbsp;3 </td>';
        echo '<td>&nbsp;4 </td>';
        echo '<td>&nbsp;5 </td>';
        echo '<td>&nbsp;6 </td>';
        echo '<td>&nbsp;7 </td>';
        echo '<td>&nbsp;8 </td>';
        echo '<td>&nbsp;9 </td>';
        echo '<td> 10 </td>';
        echo '<td> 11 </td>';
        echo '<td> 12 </td>';
        echo '<td> 13 </td>';
        echo '<td> 14 </td>';
        echo '<td> 15 </td>';
        echo '<td> 16 </td>';
        echo '<td> 17 </td>';
        echo '<td> 18 </td>';
        echo '<td> 19 </td>';
        echo '<td> 20 </td>';
        echo '<td> 21 </td>';
        echo '<td> 22 </td>';
        echo '<td> 23 </td>';
        echo '<td> 24 </td>';
        echo '<td> 25 </td>';
        echo '<td> 26 </td>';
        echo '<td> 27 </td>';
        echo '<td> 28 </td>';
        echo '<td> 29 </td>';
        echo '<td> 30 </td>';
        echo '<td> 31 </td></tr>';
        echo '<tr><td>Gen&nbsp;</td>';
        for ($n = 0; $n <= 365; $n++) {
            //if(($n==31)||($n==60)||($n==91)||($n==121)||($n==152)||($n==182)||($n==213)||($n==243)||($n==274)||($n==305)||($n==335)) {
            //  echo '</tr><tr>';
            //}
            if ($n == 31) {
                echo '</tr><tr><td>Feb&nbsp;</td>';
            }
            if ($n == 60) {
                echo '</tr><tr><td>Mar&nbsp;</td>';
            }
            if ($n == 91) {
                echo '</tr><tr><td>Apr&nbsp;</td>';
            }
            if ($n == 121) {
                echo '</tr><tr><td>Mag&nbsp;</td>';
            }
            if ($n == 152) {
                echo '</tr><tr><td>Giu&nbsp;</td>';
            }
            if ($n == 182) {
                echo '</tr><tr><td>Lug&nbsp;</td>';
            }
            if ($n == 213) {
                echo '</tr><tr><td>Ago&nbsp;</td>';
            }
            if ($n == 244) {
                echo '</tr><tr><td>Set&nbsp;</td>';
            }
            if ($n == 274) {
                echo '</tr><tr><td>Ott&nbsp;</td>';
            }
            if ($n == 305) {
                echo '</tr><tr><td>Nov&nbsp;</td>';
            }
            if ($n == 335) {
                echo '</tr><tr><td>Dic&nbsp;</td>';
            }
            if ($giorniAnno[$n] > 0) {
                echo '<td class = "cellTableEventsFull">&nbsp;&nbsp;' . $giorniAnno[$n] . '&nbsp;&nbsp;</td>';
            } else {
                echo '<td class = "cellTableEventsEmpty">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
            }
        }
        echo '</tr></table>';

        ?>
        <h3>Legenda</h3>
        <span id="oggiSTI_legenda_eventi_presenti" class="oggiSTI_table_legenda"></span><span> Giorni in cui sono presenti degli eventi</span><br />
        <span class="oggiSTI_table_legenda"></span><span> Giorni in cui non sono presenti eventi</span>

        <h2>Lista eventi pubblicati</h2>
        <?php

        $sql = "SELECT Id, Date, ItaTitle, Fb FROM published_events ORDER BY MONTH(Date),DAY(Date)";
        $result = mysqli_query($OggiSTI_conn_adm, $sql);
        while ($row = mysqli_fetch_assoc($result)) {
            $spaceString = str_replace('<', ' <', $row["ItaTitle"]);
            $doubleSpace = strip_tags($spaceString);
            $singleSpace = str_replace('  ', ' ', $doubleSpace);
            echo formatDatemmddyyyy($row["Date"]) . " <a href='../?id=" . $row["Id"] . "'>" . $singleSpace . "</a>";
            if ($row["Fb"] == 1) {
                echo ' <img class="fbIcon" src="../Assets/Img/iconFacebook.png" alt="FB Icon">';
            }
            echo "<br/>";
        }
        ?>
    </div>
    </div>


    <!-- Standard HMRWeb footer////////////////////////////////////////////////////
                    // Set:
                    // - level, 1 = "../", 2 = "../../" and so on;
                    // - set copyright start year, YYYY
                    // - set copyright end year, YYYY;
                    // - set copyright owner, default "Progetto HMR";
                    // - set date of page creation, YYYY/MM/DD.  -->

    <div class="HMR_Footer">
        <script>
            creaFooter(2, '2017', '2018', 'Nicolò Pratelli - G.A.Cignoni', '07/13/2017')
        </script>
    </div>
</body>

</html>