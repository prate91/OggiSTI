<?php

// ////////////////////////////////////////////////////////////////////////
//
// Project: OggiSTI
// Package: OggiSTI administration
// Title: Edit
// File: OggiSTI_edit.php
// Path: OggiSTI/Assets/PHP
// Type: php
// Started: 2017-03-08
// Author(s): Nicolò Pratelli
// State: in use
//
// Version history.
// - 2017.04.18 Nicolò
// First version
// - 2018.05.22 Nicolò
// Ita-eng button
// - 2018.06.06
// Updated help-block with guidelines link
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
include("../Api/config.php");
include 'OggiSTI_sessionSet.php';
include 'OggiSTI_controlLogged.php';

// Control if user has redaction permission
if($redattore==0&&$revisore==0) {
    header('Location: OggiSTI_no_permission.php');
}

// initialize empty variables
$autori="";
$messaggio = $mess = $errore = $classe = $messaggioImmagine = "";
$prev="";
$id_evento = $dateCorr = $titolo_ita = $titolo_eng = $abstr_ita = $abstr_eng = $immagine = $desc_ita = $desc_eng = $riferimenti = $keywords = $fonte_img = $commento = $state = "";


// Control if is an update or a creation 
if(isset($_GET["id_evento"])){
    // Event exists
    $menuEvento = "Modifica evento";
    $id_evento = $_GET["id_evento"];
    if(isset($_GET["id_state"])){
        $state=$_GET["id_state"];
        if($state=="Pubblicato"){
            $sql = "SELECT * FROM eventi WHERE id_evento = '$id_evento'";
        }else{
            $sql = "SELECT * FROM eventiappr WHERE id_evento = '$id_evento'";
        }
    }else{
        $sql = "SELECT * FROM eventiappr WHERE id_evento = '$id_evento'";
    }
    $result = mysqli_query($conn,$sql);
    $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
    $oldDate = $row["data_evento"];
    $date = date('d-m-Y', strtotime($oldDate));
    $dateCorr = str_replace('-', '/', $date);
    $titolo_ita = $row["titolo_ita"]; 
    $titolo_eng = $row["titolo_eng"];
    $abstr_ita = $row["abstr_ita"];
    $abstr_eng = $row["abstr_eng"];
    $immagine = $row["immagine"];
    $fonte_img = $row["fonteimmagine"];
    $desc_ita = $row["desc_ita"];
    $desc_eng = $row["desc_eng"];
    $riferimenti = $row["riferimenti"];
    $keywords = $row["keywords"];
    $autori = $row["redattore"];
    $commento = $row["commento"];
    $stato = $row["stato"];
    if($stato=="In redazione"){
        $autoreMatch="/".$id_utente."/i";
        if (preg_match($autoreMatch, $autori)) {
            $autori = $autori;
        }else{
            $autori = $autori.", ".$id_utente;
        }
    }
}else{
    // Create a new event
    $menuEvento = "Aggiungi evento";
    $sql = "INSERT INTO eventiappr (titolo_ita) VALUES ('')";
    mysqli_query($conn, $sql);    
    $risultato = mysqli_query($conn, "SELECT MAX(id_evento) FROM eventiappr");
    $riga = mysqli_fetch_array($risultato,MYSQLI_ASSOC);
    $id_evento = $riga["MAX(id_evento)"];
    $autori=$id_utente;
    $sql2 = "INSERT INTO redazione (id_evento, redattore, tipo_modifica) VALUES ('$id_evento', '$id_utente', '1')";
    mysqli_query($conn, $sql2);
}
// Check if there is an image message
if(isset($_GET["messaggioImmagine"])){
    $messaggioImmagine="<br/>".$_GET["messaggioImmagine"];
    }

// Check if there is a message
if(isset($_GET["messaggio"])){
    $mess=$_GET["messaggio"];
    if($mess=="salva"){
        $messaggio="Evento salvato correttamente".$messaggioImmagine;
        $classe="alert alert-success";
    }
    if($mess=="modifica"){
        $messaggio="Stai modificando un evento".$messaggioImmagine;
        $classe="alert alert-warning";
    }
    if($mess=="modificaVeloce"){
        $messaggio="Stai modificando un evento senza lasciare traccia della redazione".$messaggioImmagine;
        $classe="alert alert-danger";
    }
    if($mess=="errore"){
        $messaggio="Evento NON salvato".$messaggioImmagine;
        $classe="alert alert-danger";
    }
    
}else{
    $messaggio="Hai creato un nuovo evento".$messaggioImmagine;
    $classe="alert alert-warning";
}




// Control if launch the preview
if(isset($_GET["preview"])){
    $prev = $_GET["preview"];
    if($prev=="ok"){
        $link = "<script>window.open(\"../../OggiSTI_preview.php?id_evento=$id_evento&id_state=Preview\", \"previewOggiSTI\", \"width=864,height=1000\")</script>";
        echo $link;       
        
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


<script src='../../../Assets/Libs/jQuery/jquery-3.3.1.min.js'></script>
<script src='../../../Assets/Libs/jQuery-UI/jquery-ui.js'></script>
<script src='../JS/datepicker-it.js'></script>
<script src='../../../Assets/Libs/Bootstrap/JS/bootstrap.js'></script>
<script src="https://www.w3schools.com/lib/w3.js"></script>
<script src="../../../Assets/Libs/tinymce/jquery.tinymce.min.js"></script>
<script src="../../../Assets/Libs/tinymce/tinymce.min.js"></script>

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

<!--<div id="visualizzaCommento" class="alert alert-info">
<strong>Commento:</strong> <?php echo $commento; ?>
</div>-->
    


<div class="jumbotron">
<!-- Comment -->
<div id="visualizzaCommento" class="panel panel-default">
    <div class="panel-body">Commento: <br/> <?php echo $commento; ?> </div>
</div>
<br class="stop"/>
<!-- Message alert -->
<div class="<?php echo $classe; ?>" id="alertEvento">
    <button type="button" class="close" data-dismiss="alert">x</button>
    <p><?php echo $messaggio; ?></p>
</div>
<!-- Language button -->
<div id="btnLanguage">
    <button id="btnItalian" class="btn active">Italiano</button><button id="btnEnglish" class="btn">English</button>
</div>

<!-- Form starts here -->
<form id="addEvent" method="post" action="../Api/updateEvents.php" enctype="multipart/form-data">

    <!-- ID -->
    <div class="col-xs-2">
    <label for="id_evento">Id evento</label>
	<input type="text" name="id_evento" class="form-control" id="id_evento" readonly value="<?php if($mess=="errore"){echo $_SESSION['id_evento'];}else{ echo $id_evento;} ?>">
    </div>

	<!-- Date -->
	<div id="formData" class="form-group col-xs-10">
	<label for="date">Data dell'evento</label>
	<input type="datetime" name="date" class="form-control" id="date" placeholder="dd/mm/yyyy" value="<?php if($mess=="errore"){echo $_SESSION['data_evento'];}else{echo $dateCorr;} ?>">
	<span id="glyphiconDate"></span>
	<span id="helpDate" class="help-block">Per maggiori informazioni consultare le <a href="../../LineeGuida/#LGData" target="_blank">linee guida sulla data</a></span>
	</div>

	<br class="stop"/>

	<!-- Title -->
    <h2 id="editTitle">Titolo</h2>
    <!-- Italian -->
	<div id="formTitle_ita" class="form-group col-xs-12">
	<label for='title_ita'>in italiano</label>
	<textarea name="title_ita" class="form-control" rows="2" id="title_ita"><?php if($mess=="errore"){echo $_SESSION['titolo_ita'];}else{ echo $titolo_ita;} ?></textarea>
	<span id="glyphiconTitleIta"></span>
	<span id="countBox_title_ita" class="help-block pull-right">140</span>
	<span id="helpTitleIta" class="help-block">La dimensione massima consigliata è di 70 caratteri. Per maggiori informazioni consultare le <a href="../../LineeGuida/#LGTitolo" target="_blank">linee guida sul titolo</a></span>
	</div>
    <!-- English -->
	<div id="formTitle_eng" class="form-group col-xs-12 hidden">
	<label for='title_eng'>in English</label>
	<textarea name="title_eng" class="form-control" rows="2" id="title_eng"><?php if($mess=="errore"){echo $_SESSION['titolo_eng'];}else{ echo $titolo_eng;} ?></textarea>
	<span id="glyphiconTitleEng"></span>
	<span id="countBox_title_eng" class="help-block pull-right">140</span>
	<span id="helpTitleEng" class="help-block">The maximum recommended size is 70 characters. For more information consult <a href="../../LineeGuida/#LGTitolo" target="_blank">title's guidelines</a></span>
	</div>

	<!-- Image -->
    <br/>
    <h2 id="editImg" class="custom-file">Immagine</h2>
    <img id="oggiSTI_immagineEvento" src="<?php if($mess=="errore"){echo "../Img/eventi/".$_SESSION['immagine'];}else{ echo "../Img/eventi/".$immagine;} ?>" alt="Nessuna immagine precedente"/>
    <div class="col-xs-8">
    <input type="text" name="vecchiaImmagine" class="form-control" id="vecchiaImmagine" readonly value="<?php if($mess=="errore"){echo $_SESSION['immagine'];}else{ echo $immagine;} ?>">
	<br/>
    </div>
    <div class="col-xs-4">
	<input type="file" name="immagine" id="immagine" class="custom-file-input"/>
    </div>
    <br class="stop" />
    <!-- Reference Image -->
    <div class="col-xs-12">
    <label for='abstr_ita'>Fonte dell'immagine</label>
	<textarea name="fonte_img" class="form-control" rows="1" id="fonte_img"><?php if($mess=="errore"){echo $_SESSION['fonte_img'];}else{ echo $fonte_img;} ?></textarea>
    <span id="helpImg" class="help-block">Per maggiori informazioni consultare le <a href="../../LineeGuida/#LGImm" target="_blank">linee guida sull'immagine</a></span>
    </div>

	<br class="stop" />
	<!-- Icona 
	<br/>
	<label class="custom-file">Icona</label>
	<input type="file" name="icona" id="icona" class="custom-file-input">
	<span class="custom-file-control"></span>
	<br/>
    -->
	<br class="stop" />

	<!-- Brief description --> 
    <h2 id="editDesc">Descrizione breve</h2>
    <!-- Italian -->
	<div id="formAbstr_ita" class="form-group col-xs-12">
    <label for='abstr_ita'>in Italiano</label>
	<textarea name="abstr_ita" class="form-control textControl" rows="5" id="abstr_ita"><?php if($mess=="errore"){echo $_SESSION['abstr_ita'];}else{ echo $abstr_ita;} ?></textarea>
	<span id="glyphiconAbstrIta"></span>
	<span id="helpAbstrIta" class="help-block">La dimensione massima consigliata è di 30 parole. Per maggiori informazioni consultare le <a href="../../LineeGuida/#LGDescr" target="_blank">linee guida sulla descrizione</a></span>
    </div>
    <!-- English -->
	<div id="formAbstr_eng" class="form-group col-xs-12 hidden">
	<label for='abstr_eng'>in English</label>
    <a href="#" data-toggle="tooltip" title="Brief description have to provide information to excite curiosity. The recommended size is about 30 words."><span class="glyphicon glyphicon-info-sign"></span></a>
	<textarea name="abstr_eng" class="form-control textControl" rows="5" id="abstr_eng"><?php if($mess=="errore"){echo $_SESSION['abstr_eng'];}else{ echo $abstr_eng;} ?></textarea>
	<span id="glyphiconAbstrEng"></span>
	<span id="helpAbstrEng" class="help-block">The maximum recommended size is 30 words. For more information consult <a href="../../LineeGuida/#LGDescr" target="_blank">description's guidelines</a></span>
    </div>

	<br class="stop" />

	<!-- Deep description --> 
    <h2 id="editDeep">Descrizione di approfondimento</h2>
    <!-- Italian -->
	<div id="formDesc_ita" class="form-group col-xs-12">
	<label for='desc_ita'>in italiano</label>
	<textarea name="desc_ita" class="form-control longTextControl" rows="10" id="desc_ita"><?php if($mess=="errore"){echo $_SESSION['desc_ita'];}else{ echo $desc_ita;} ?></textarea>
    <span id="helpDescIta" class="help-block">La dimensione massima consigliata è di 150 parole. Per maggiori informazioni consultare le <a href="../../LineeGuida/#LGDescr" target="_blank">linee guida sulla descrizione</a></span>
	</div>
	<!-- English -->
	<div id="formDesc_eng" class="form-group col-xs-12 hidden">
	<label for='desc_eng'>in English</label>
	<textarea name="desc_eng" class="form-control longTextControl" rows="10" id="desc_eng"><?php if($mess=="errore"){echo $_SESSION['desc_eng'];}else{ echo $desc_eng;} ?></textarea>
    <span id="helpDescEng" class="help-block">The maximum recommended size is 150 words. For more information consult <a href="../../LineeGuida/#LGDescr" target="_blank">description's guidelines</a></span>
	</div>

    <br class="stop" />
	
    <!-- References -->
    <h2 id="editRef">Riferimenti</h2>
    <div id="formRiferimenti" class="form-group col-xs-12">
	<label for='riferimenti'>Riferimenti</label>
	<textarea name="riferimenti" class="form-control textControl" rows="5" id="riferimenti"><?php if($mess=="errore"){echo $_SESSION['riferimenti'];}else{ echo $riferimenti;} ?></textarea>
    <span id="helpRiferimenti" class="help-block">Consultare le linee guida sul <a href="../../ChicagoStyle/" target="_blank">Chicago Manual of Style</a></span>
	<br/>
	</div>

	<!-- Keywords --> 
    <h2 id="editKey">Parole chiave</h2>
    <div class="col-xs-12">
	<label for="keywords">Keywords</label>
    <a href="#" data-toggle="tooltip" title="Inserire le parole chiave relative all'evento"><span class="glyphicon glyphicon-info-sign"></span></a>
    <input type="text" name="keywords" class="form-control" id="keywords" value="<?php if($mess=="errore"){echo $_SESSION['keywords'];}else{  echo $keywords;} ?>">
	<span class="help-block">Separare le parole con un punto e virgola (;)</span>
    </div>
	<br/>

    <!-- Author -->
     <div class='col-lg-12 hidden'>
    <label for="autore">Autori</label>
	<input type="text" name="autore" class="form-control" id="autore" readonly value="<?php echo $autori; ?>">
    </div>
	<br/>
         
    <!-- Saved by -->
    <div class='col-lg-2 hidden'>
    <label for="salvato">Salvato da:</label>
	<input type="text" name="salvato" class="form-control" id="salvato" readonly value="<?php echo $id_utente; ?>">
    </div>

	<!-- State -->
    <?php if($redattore == 1 && $revisore == 0 && $state!="Pubblicato"){
        echo "<div class='col-lg-3 hidden'>";
        echo "<label for='Iapprovazione'>I approvazione</label>
        <input type='text' name='Iapprovazione' class='form-control col-lg-2' id='Iapprovazione' readonly value='0'></div>";
        echo "<div class='col-lg-3 hidden'>";
        echo "<label for='IIapprovazione'>II approvazione</label>
        <input type='text' name='IIapprovazione' class='form-control col-lg-2' id='IIapprovazione' readonly value='0'></div>";
        echo "<div class='col-lg-4 hidden'>";
        echo "<label for='stato'>Stato</label>
        <input type='text' name='stato' class='form-control col-lg-2' id='stato' readonly value='Approvazione 0/2'></div>";
        echo "<br class='stop'/>";
    }else if($redattore == 1 && $revisore == 1 && $state!="Pubblicato"){
        echo "<div class='col-lg-3 hidden'>";
        echo "<label for='Iapprovazione'>I approvazione</label>
        <input type='text' name='Iapprovazione' class='form-control id='Iapprovazione' readonly value='".$id_utente."'></div>";
        echo "<div class='col-lg-3 hidden'>";
        echo "<label for='IIapprovazione'>II approvazione</label><br/>
        <input type='text' name='IIapprovazione' class='form-control' id='IIapprovazione' readonly value='0'></div>";
        echo "<div class='col-lg-4 hidden'>";
        echo "<label for='stato'>Stato</label><br/>
        <input type='text' name='stato' class='form-control' id='stato' readonly value='Approvazione 1/2'></div>";
        echo "<br class='stop'/>";
    }else if($state=="Pubblicato"){
        echo "<div class='col-lg-4 hidden'>";
        echo "<label for='stato'>Stato</label><br/>
        <input type='text' name='stato' class='form-control' id='stato' readonly value='Pubblicato'></div>";
        echo "<br class='stop'/>";
     }
    ?>
    <br/>

    <!-- Buttons -->
    <?php 
    if ($mess=="modificaVeloce"){
        echo '<input type="submit" name="salvaChiudi" id="salvaChiudi" class="btn btn-success" value="Salva e chiudi">';
    } else{
        echo '<div class="pull-right">
    <input type="submit" name="salva" id="salva" class="btn btn-success" value="Salva">
    <button type="submit" name="preview" id="preview" class="btn btn-warning">Preview</button>
	<button type="button" id="applica" class="btn btn-info" data-toggle="modal" data-target="#modalApprovazione">Invia in approvazione</button>
    </div>';
    }
    ?>

    <!-- Approvation modal -->
    <div id="modalApprovazione" class="modal fade">
    <div class="modal-dialog">
    <div class="modal-content">
    <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title">Invia l'evento in approvazione</h4>
    </div>
    <div class="modal-body">
    <p class="alert alert-info">Stai inviando l'evento in approvazione. Se vuoi fare altre modifiche clicca su annulla.</p>
    <p class="alert alert-danger" id="campiMancanti"></p>
    </div>
    <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Annulla</button>
    <input type="submit" name="invia" id="invia" class="btn btn-info" value="Invia in approvazione">
    </div>
    </div>
    </div>
    </div>
</form>
<!-- Form ends here -->
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
