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
include("../../../Administration/Assets/Api/configUtenti.php");
include("../Api/config.php");
include 'OggiSTI_sessionSet.php';
include 'OggiSTI_controlLogged.php';

// initialize empty variables
    $messaggio = $mess = $errore = $notizia = "";
    $id_evento = $dateCorr = $titolo_ita = $titolo_eng = $abstr_ita = $abstr_eng = $immagine = $desc_ita = $desc_eng = $riferimenti = $keywords = $salvato = $fonte_img = $commento = "";
    $fb=0;
    if(isset($_GET["id_evento"])&&isset($_GET["id_state"])) {
        $menuEvento = "Modifica evento";
        $id_evento = $_GET["id_evento"];
        $id_state = $_GET["id_state"];
        if($id_state=="Pubblicato"){
            $sql = "SELECT * FROM eventi WHERE id_evento = '$id_evento'";
        } else {
            $sql = "SELECT * FROM eventiappr WHERE id_evento = '$id_evento'";
        }
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $oldDate = $row["data_evento"];
        $date = date('d-m-Y', strtotime($oldDate));
        $dateCorr = str_replace('-', '/', $date);
        $titolo_ita = $row["titolo_ita"];
        $titolo_eng = $row["titolo_eng"];
        $abstr_ita = $row["abstr_ita"];
        $abstr_eng = $row["abstr_eng"];
        $immagine = $row["immagine"];
        $icona = $row["icona"];
        $fonte_img = $row["fonteimmagine"];
        $desc_ita = $row["desc_ita"];
        $desc_eng = $row["desc_eng"];
        $riferimenti = $row["riferimenti"];
        $keywords = $row["keywords"];
        $autori = $row["redattore"];
        $pieces = explode(", ", $autori);
        $riga_redattori = "";
        for($j=0; $j<sizeof($pieces); $j++){
            $id_utente = intval($pieces[$j]);
            $queryUtenti = "SELECT * FROM admin WHERE id_auth=$id_utente";
            $risultato_query_utenti = mysqli_query($connUtenti, $queryUtenti);
            $riga_utente = mysqli_fetch_array($risultato_query_utenti,MYSQLI_ASSOC);
            $riga_redattori =  $riga_redattori . $riga_utente["nome"] . " " . $riga_utente["cognome"]. "<br/> ";
        }
        $revisore_1 = "";
        $ver_1 = $row["ver_1"];
        if($ver_1!=0){
            $id_utente = intval($ver_1);
            $queryUtenti = "SELECT * FROM admin WHERE id_auth=$id_utente";
            $risultato_query_utenti = mysqli_query($connUtenti, $queryUtenti);
            $riga_utente = mysqli_fetch_array($risultato_query_utenti,MYSQLI_ASSOC);
            $revisore_1 =  $riga_utente["nome"] . " " . $riga_utente["cognome"];
        }
        $ver_2 = $row["ver_2"];
        $revisore_2 = "";
        if($ver_2!=0){
            $id_utente = intval($ver_2);
            $queryUtenti = "SELECT * FROM admin WHERE id_auth=$id_utente";
            $risultato_query_utenti = mysqli_query($connUtenti, $queryUtenti);
            $riga_utente = mysqli_fetch_array($risultato_query_utenti,MYSQLI_ASSOC);
            $revisore_2 =  $riga_utente["nome"] . " " . $riga_utente["cognome"];
        }
        $stato = $row["stato"];
        if($id_state!="Pubblicato"){
            $salvato = $row["salvato"];
            if($salvato!=0){
                $id_utente = intval($salvato);
                $queryUtenti = "SELECT * FROM admin WHERE id_auth=$id_utente";
                $risultato_query_utenti = mysqli_query($connUtenti, $queryUtenti);
                $riga_utente = mysqli_fetch_array($risultato_query_utenti,MYSQLI_ASSOC);
                $salvato =  $riga_utente["nome"] . " " . $riga_utente["cognome"];
            }
        }
        $usato = $row["usato"];
        $commento = $row["commento"];
        if($id_state=="Pubblicato"){
            $fb = intval($row["fb"]);
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
    
<meta name="description" content="HMR.OggiSTI: lineeguida" />

<meta name="keywords" content="hackerando hacker hacking macchina ridotta calcolatrice elettronica pisana CEP electronic computer storia history informatica computer science archeologia archeology sperimentale experimental ricostruzioni rebuilding replica repliche replicas simulazione simulation simulatori simulators macchine passato past machines 
documenti documents cignoni giovanni pratelli nicolò oggi almanacco oggisti" />


<!-- meta Facebook Open Graph -->
<meta id="metaImage" property="og:image" content="Asset/Img/logo-oggiSTI.png"/>
<meta id="metaTitle" property="og:title" content="Oggi nella storia dell'informatica"/>
<meta id="metaDescription" property="og:description" content="Almanacco per la diffusione della storia dell'informatica" />
<meta property="og:url" content="https://progettohmr.it/oggiSTI/"/>
<meta property="og:site_name" content="OggiSTI"/>
<meta property="og:type" content="website"/>



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
        <tr><td>Evento:</td><td id='idEvento'><?php echo $id_evento; ?></td></tr>
        <tr><td>Data:</td><td><?php echo $dateCorr; ?></td></tr>
        <tr><td>Titolo:</td><td><?php echo $titolo_ita; ?></td></tr>
        <tr><td>Title:</td><td><?php echo $titolo_eng; ?></td></tr>
        <tr><td>Immagine:</td><td><img id='oggiSTI_immagineEvento' src='../Img/eventi/<?php echo $immagine; ?>' alt='Nessuna immagine'/></td></tr>
        <tr><td>Link immagine:</td><td><?php echo $immagine; ?></td></tr>
        <tr><td>Fonte immagine:</td><td><?php echo $fonte_img; ?></td></tr>
        <tr><td>Link icona:</td><td><?php echo $icona; ?></td></tr>
        <tr><td>Descrizione Breve:</td><td><?php echo $abstr_ita; ?></td></tr>
        <tr><td>Brief description:</td><td><?php echo $abstr_eng; ?></td></tr>
        <tr><td>Descrizione:</td><td><?php echo $desc_ita; ?></td></tr>
        <tr><td>Description:</td><td><?php echo $desc_eng; ?></td></tr>
        <tr><td>Riferimenti:</td><td><?php echo $riferimenti; ?></td></tr>
        <tr><td>Keywords:</td><td><?php echo $keywords; ?></td></tr>
        <tr><td>Redattore:</td><td><?php echo $riga_redattori; ?></td></tr>
        <tr><td>Verifica 1:</td><td><?php echo $revisore_1; ?></td></tr>
        <tr><td>Verifica 2:</td><td><?php echo $revisore_2; ?></td></tr>
        <tr><td>Stato:</td><td id='idStato'><?php echo $stato; ?></td></tr>
        <tr><td>Salvato:</td><td><?php echo $salvato; ?></td></tr>
        <tr><td>Usato:</td><td><?php echo $usato; ?> volta/e</td></tr>
        <tr><td>Facebook:</td><td><?php if($fb==0){ echo "non pubblicabile";}else{echo "pubblicabile";} ?></td></tr>
        <tr class='rigaCommento'><td>Commento:</td><td><?php echo $commento; ?></td></tr>
    </table>
    <?php if(($stato=="Approvazione 0/2"||$stato=="Approvazione 1/2"||$stato=="Pubblicato")&&($revisore==1)) {
        echo '<form id = "formCommento" method = "post" action = "../Api/updateReview.php" class="form-horizontal">';
        echo '<div id = "spazioCommento" class="form-group">';
        echo "<input type = 'hidden' class='hidden_id_evento' name = 'id_evento' value = '$id_evento' />";
        echo "<input type = 'hidden' id = 'hidden_ver_1' name = 'ver_1' value = '$ver_1' />";
        echo "<input type = 'hidden' id = 'hidden_ver_2' name = 'ver_2' value = '$ver_2' />";
        echo '<label for="comment" > Commento:</label >';
        echo '<textarea class="form-control" name = "commento" rows = "5" id = "comment" ></textarea >';
        echo '<span class="help-block" > Inserisci un commento </span>';
    }?>
    <?php
        echo '<div id="bottoniCommento" class="">';

        if((($stato=="In redazione")&&($redattore==1)&&(($salvato==$id_utente)||($salvato==0)))) {
            echo '<button type = "button" id = "modificaEvento" class="btn btn-warning" > Modifica Evento </button>';
        }

        if($revisore==1 && $stato!="In redazione") {
            echo '<button type = "button" id = "modificaVeloce" class="btn btn-warning" > Modifica Veloce </button>';
        }
        if((($stato=="Approvazione 0/2")||($stato=="Approvazione 1/2"))&&($revisore==1)) {
            echo '<button type = "submit" name = "redazione" id = "redazione" class="btn btn-default" > Manda in redazione </button >';
            echo '<button type = "submit" name = "approva" id = "approva" class="btn btn-default" > Approva</button >';
        }
        if($stato=="Pubblicato"){
        echo '<button type = "submit" name = "redazionePubblicato" id = "redazionePubblicato" class="btn btn-default" > Manda in redazione </button>';
        if($fb==0){
            echo '<button type = "submit" name = "facebookOn" id = "facebookOn" class="btn btn-primary"> Facebook ON </button>';
        }else{
             echo '<button type = "submit" name = "facebookOff" id = "facebookOff" class="btn btn-danger">  Facebook OFF  </button>';
        } 

    echo "</div>";
        
    echo "</form>";
            }?>

    <h2>Cronologia delle modifiche</h2>
    <ul>
    <?php
        $queryUtente = "SELECT * FROM admin WHERE id_auth='$redattore'";
        $sql2 = "SELECT * FROM redazione WHERE id_evento='$id_evento'";
        $result2 = mysqli_query($conn, $sql2);
        // $row = mysqli_fetch_array($result2, MYSQLI_ASSOC);
        while ($row2 = mysqli_fetch_assoc($result2)) {
            $redattore=$row2["redattore"];
            $queryUtente = "SELECT * FROM admin WHERE id_auth='$redattore'";
            $resultQueryUtente = mysqli_query($connUtenti, $queryUtente);
            $rowUtente = mysqli_fetch_array($resultQueryUtente,MYSQLI_ASSOC);
            switch ($row2["tipo_modifica"]) {
                case 1:
                    $tipo="creato";
                    break;
                case 2:
                    $tipo="salvato";
                    break;
                case 3:
                    $tipo="inviato in approvazione";
                    break;
                case 4:
                    $tipo="modifica rapida";
            }
    echo "<li>" . $row2["data"] . " - " . $rowUtente["cognome"] . " " . $rowUtente["nome"] . " - ".  $tipo ."</li>";
            }
        ?>
    </ul>

    <h2>Cronologia delle revisioni</h2>
    <ul>
    <?php
        $queryUtente = "SELECT * FROM admin WHERE id_auth='$redattore'";
        $sql2 = "SELECT * FROM revisione WHERE id_evento='$id_evento'";
        $result2 = mysqli_query($conn, $sql2);
        // $row = mysqli_fetch_array($result2, MYSQLI_ASSOC);
        while ($row2 = mysqli_fetch_assoc($result2)) {
            $revisore=$row2["revisore"];
            $queryUtente = "SELECT * FROM admin WHERE id_auth='$revisore'";
            $resultQueryUtente = mysqli_query($connUtenti, $queryUtente);
            $rowUtente = mysqli_fetch_array($resultQueryUtente,MYSQLI_ASSOC);
            switch ($row2["tipo_revisione"]) {
                case 1:
                    $tipo="approvato";
                    break;
                case 2:
                    $tipo="inviato in redazione";
                    break;
                case 3:
                    $tipo="pubblicabile su Facebook";
                    break;
                case 4:
                    $tipo="non pubblicabile su Facebook";
                    break;
            }
    echo "<li>" . $row2["data"] . " - " . $rowUtente["cognome"] . " " . $rowUtente["nome"] . " - ".  $tipo ."</li>";
            }
        ?>
    </ul>

</div>
<span class="stop"></span>

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

