<?php
// ////////////////////////////////////////////////////////////////////////
//
// Project: OggiSTI
// Package: OggiSTI administration
// Title: Event
// File: OggiSTI_event.php
// Path: OggiSTI/Assets/PHP
// Type: php
// Started: 2018-05-19
// Author(s): Nicolò Pratelli
// State: in use
//
// Version history.
// - 2018.05.19 Nicolò
// First version
// - 2018.10.25 Nicolò
// Updated facebook buttons
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

// include PHP files

require_once __DIR__ . '/OggiSTI_sessionSet.php';
require_once __DIR__ . '/OggiSTI_controlLogged.php';
require_once __DIR__ . '/../Api/Utils/functions.php';
require_once __DIR__ . '/../Api/Objects/Event.class.php';


$OggiSTI_db = DatabaseConfig::OggiSTIDBConnect();

// initialize empty variables
$message = $mess = $errore = $notizia = "";
$eventId = $dateCorr = $itaTitle = $engTitle = $itaAbstract = $engAbstract = $image = $itaDescription = $engDescription = $textReferences = $keywords = $saved = $savedName = $imageCaption = $comment = $editors = "";
$fb = 0;

$event = new Event();

if (isset($_GET["eventId"]) && isset($_GET["stateId"])) {
    $menuEvento = "Modifica evento";
    $eventId = $_GET["eventId"];
    $stateId = $_GET["stateId"];
    $event->read();
    $editorsRow = buildEditors($event->getEditors());
    $nameReviser1 = "";
    $nameReviser2 = "";
    $reviser1 = $event->getReviser1();
    $reviser2 = $event->getReviser2();

    $nameReviser1 = buildReviser($reviser1);
    $nameReviser2 = buildReviser($reviser2);

    $state = $event->getState();
    if ($stateId != "Pubblicato") {
        $saved = $event->getSaved();
        if ($saved != 0) {
            $idUser = intval($saved);
            $savedName = loadCompletefName(loadPeopleId($idUser));
        }
    }

    if ($stateId == "Pubblicato") {
        $fb = intval($event->getFb());
    }

}

?>
<!DOCTYPE html><html lang='it'><head><meta charset="UTF-8">

<title>Oggi nella storia dell'informatica - HMR</title>

<!-- Load OggiSTI standard libraries -->
<link rel='stylesheet' href='../../../Assets/Libs/Bootstrap/CSS/bootstrap.css'>
<link rel='stylesheet' href='../../../Assets/Libs/jQuery-UI/jquery-ui.css'>
<link rel='stylesheet' href='../../../Assets/Libs/jQuery-UI/jquery-ui.theme.css'>
<link rel='stylesheet' href='../../../Assets/Libs/jQuery-UI/jquery-ui.structure.css'>
<link rel='stylesheet' href='../../../Assets/Libs/DataTables/datatables.min.css'>


<script src='../../../Assets/Libs/jQuery/jquery-3.3.1.min.js'></script>
<script src='../../../Assets/Libs/jQuery-UI/jquery-ui.js'></script>
<script src='../JS/datepicker-it.js'></script>
<script src='../../../Assets/Libs/Bootstrap/JS/bootstrap.js'></script>
<script src="https://www.w3schools.com/lib/w3.js"></script>
<script src="../../../Assets/Libs/DataTables/datatables.min.js"></script>

<!-- Load HMR CSS styles & fonts -->
<link rel="stylesheet" type="text/css" href="../../../HMR_Style.css">

<!-- Load OggiSTI CSS styles & fonts -->
<link rel="stylesheet" type="text/css" href="../CSS/OggiSTI_Style.css">

<!-- Load favorite icon -->
<link rel="icon" type="image/png" href="../Img/logo-oggiSTI16x16.png" />

<!-- Load HMR standard libraries -->
<script type='text/javascript' src='../../../EPICAC/JSwebsite/searchAndSharing.js'></script>
<script type='text/javascript' src='../../../Assets/JS/HMR_CreaHTML.js'></script>

<!-- Load OggiSTI standard Javascript -->
<script src='../JS/OggiSTI_function.js'></script>

<!-- Load OggiSTI Almanac Javascript -->
<script src='../JS/OggiSTI.js'></script>
<script src='../JS/OggiSTI_almanac.js'></script>
    
<!-- To prevent most search engine crawlers indexing this page -->  
<meta name="robots" content="noindex">

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
	<script> creaHeader(3, 'HMR_2017g_GC-WebHeaderRite-270x105-3.png') </script>
</div>	
<div id="HMR_Menu" class="HMR_Menu" >
	<script> creaMenu(3, 5) </script>
</div>
	
	
<span class="stop"></span>
	
<!-- Actual page content starts here ///////////////////////////////////////-->
<div class="oggiSTI_content_amm">
<!-- OggiSTI navbar menu -->
<?php
include 'OggiSTI_navbarMenu.php';
?>

    <table id="eventoAperto" class="table table-striped display"  width="100%" cellspacing="0">
        <tr><td>Evento:</td><td id='idEvento'><?php echo $event->getId(); ?></td></tr>
        <tr><td>Data:</td><td><?php echo $event->getDate(); ?></td></tr>
        <tr><td>Titolo:</td><td><?php echo $event->getItaTitle(); ?></td></tr>
        <tr><td>Title:</td><td><?php echo $event->getEngTitle(); ?></td></tr>
        <tr><td>Immagine:</td><td><img id='oggiSTI_immagineEvento' src='../Img/eventi/<?php echo $event->getImage(); ?>' alt='Nessuna image'/></td></tr>
        <tr><td>Link image:</td><td><?php echo $event->getImage(); ?></td></tr>
        <tr><td>Fonte image:</td><td><?php echo $event->getImageCaption(); ?></td></tr>
        <tr><td>Link icon:</td><td><?php echo $event->getIcon(); ?></td></tr>
        <tr><td>Descrizione Breve:</td><td><?php echo $event->getItaAbstract(); ?></td></tr>
        <tr><td>Brief description:</td><td><?php echo $event->getEngAbstract(); ?></td></tr>
        <tr><td>Descrizione:</td><td><?php echo $event->getItaDescription(); ?></td></tr>
        <tr><td>Description:</td><td><?php echo $event->getEngDescription(); ?></td></tr>
        <tr><td>Riferimenti:</td><td><?php echo $event->getTextReferences(); ?></td></tr>
        <tr><td>Keywords:</td><td><?php echo $event->getKeywords(); ?></td></tr>
        <tr><td>Redattore:</td><td><?php echo $editorsRow; ?></td></tr>
        <tr><td>Verifica 1:</td><td><?php echo $nameReviser1; ?></td></tr>
        <tr><td>Verifica 2:</td><td><?php echo $nameReviser2; ?></td></tr>
        <tr><td>Stato:</td><td id='idStato'><?php echo $event->getState(); ?></td></tr>
        <tr><td>Salvato:</td><td><?php echo $savedName; ?></td></tr>
        <tr><td>Usato:</td><td><?php echo $event->getViews(); ?> volta/e</td></tr>
        <tr><td>Facebook:</td><td>
        <?php 
        echo '<form id = "formCommento" method = "post" action = "../Api/Update/updateReview.php" class="form-horizontal">';
        if ($fb == 0) {
            echo '<button class="btn btn-danger" disabled> Non pubblicabile </button>';
            if ($state == "Pubblicato" && $reviserPermission == 1) {
                echo '<button type = "submit" name = "facebookOn" id = "facebookOn" class="btn btn-success btn-circle"> ON </button>';
            }
        } else {
            echo '<button class="btn btn-success" disabled> Pubblicabile </button>';
            if ($state == "Pubblicato" && $reviserPermission == 1) {
                echo '<button type = "submit" name = "facebookOff" id = "facebookOff" class="btn btn-danger btn-circle"> OFF </button>';
            }
        }
        ?></td></tr>
        <tr class='rigaCommento'><td>Commento:</td><td><?php echo $comment; ?></td></tr>
    </table>
    <?php if (($state == "Approvazione 0/2" || $state == "Approvazione 1/2" || $state == "Pubblicato") && ($reviserPermission == 1)) {

        echo '<div id = "spazioCommento" class="form-group">';
        echo "<input type = 'hidden' class='hidden_eventId' name = 'eventId' value = '$eventId' />";
        echo "<input type = 'hidden' id = 'hidden_reviser1' name = 'reviser1' value = '$reviser1' />";
        echo "<input type = 'hidden' id = 'hidden_reviser2' name = 'reviser2' value = '$reviser2' />";
        echo '<label for="comment" > Commento:</label >';
        echo '<textarea class="form-control" name = "comment" rows = "5" id = "comment" ></textarea >';
        echo '<span class="help-block" > Inserisci un comment </span>';
    } ?>
    <?php
    echo '<div id="bottoniCommento" class="btn-group">';

        // Edit event button, only if isn't saved or saved by user that has editing permission
        // and the state is "In editing"
    if ((($state == "In redazione") && ($editorPermission == 1) && (($saved == $userId) || ($saved == 0)))) {
        echo '<button type = "button" id = "modificaEvento" class="btn btn-warning" > Modifica Evento </button>';
    }

        // Quick change button, only if user has review permission 
        // and event isn't in editing state
    if ($reviserPermission == 1 && $state != "In redazione") {
        echo '<button type = "button" id = "modificaVeloce" class="btn btn-warning" > Modifica Veloce </button>';
    }

        // Send in editing and approve buttons, only if event is in review states 
        // and user has review permission
    if ((($state == "Approvazione 0/2") || ($state == "Approvazione 1/2")) && ($reviserPermission == 1)) {
        echo '<button type = "submit" name = "redazione" id = "redazione" class="btn btn-default" > Manda in redazione </button >';
        echo '<button type = "submit" name = "approva" id = "approva" class="btn btn-default" > Approva</button >';
    }

        // Send in editing from published state
    if ($state == "Pubblicato") {
        echo '<button type = "submit" name = "redazionePubblicato" id = "redazionePubblicato" class="btn btn-default" > Manda in redazione </button>';
    }

    echo "</div>";


    echo "<p>Admin buttons</p>";
    echo '<div class="btn-group">';
    if ($administratorPermission == 1) {
        echo '<button type="button" id="updateEventState" class="btn btn-danger" data-toggle="modal" data-target="#updateStateModal">Aggiorna stato</button>';
    }
    echo "</div>";

    echo "</form>";
    ?>

    <h2>Cronologia delle modifiche</h2>
    <ul class="fixed-panel">
    <?php echo loadEditingChronology($eventId); ?>
    </ul>

    <h2>Cronologia delle revisioni</h2>
    <ul class="fixed-panel"> 
    <?php echo loadReviewChronology($eventId); ?>
    </ul>

</div>
<span class="stop"></span>
    <!-- Update state modal -->
    <div id="updateStateModal" class="modal fade">
    <div class="modal-dialog">
    <div class="modal-content">
    <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title">Cambia lo stato dell'evento</h4>
    </div>
    <div class="modal-body">
    <form id="updateStateForm" method="post" action="../Api/Update/updateState.php">
    <div class="form-group">
        <input type="text" class="form-control hidden" name="eventId" value="<?php echo $eventId; ?>" readonly>
        <label for="selectCommand">Modifica evento</label>
        <select class="form-control" name="selectCommand">
            <?php
            if (isset($_GET["stateId"])) {
                $stateId = $_GET["stateId"];
                if ($stateId == "Pubblicato") {
                    echo "<option value='makeSleepyPublished'>Sposta tra i dormienti</option>";
                    echo "<option value='makeEditingPublished'>Rendi disponibile</option>";
                } else {
                    echo "<option value='makeSleepy'>Sposta tra i dormienti</option>";
                    echo "<option value='makeEditing'>Rendi disponibile</option>";
                }
            }
            ?>
        </select>
    </div> 
    </div>
    <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Annulla</button>
    <input type="submit" name="updateState" id="updateState" class="btn btn-warning" value="Effettua la modifica">
    </form>
    </div>
    </div>
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
    <script> creaFooter(3, '2017', '2018', 'Nicolò Pratelli - G.A.Cignoni', '07/13/2017') </script>
</div>
</body>
</html>

