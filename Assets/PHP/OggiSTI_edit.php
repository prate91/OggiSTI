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
require("../../../../Config/OggiSTI_config_adm.php");
include 'OggiSTI_sessionSet.php';
include 'OggiSTI_controlLogged.php';

// Control if user has redaction permission
if($editorPermission==0&&$reviserPermission==0) {
    header('Location: OggiSTI_no_permission.php');
}

// initialize empty variables
$editors="";
$message = $mess = $errore = $class = $imageMessage = "";
$prev="";
$eventId = $dateCorr = $itaTitle = $engTitle = $itaAbstract = $engAbstract = $image = $editors = $itaDescription = $engDescription = $textReferences = $keywords = $imageCaption = $comment = $state = "";


// Control if is an update or a creation 
if(isset($_GET["eventId"])){
    // Event exists
    $menuEvento = "Modifica evento";
    $eventId = $_GET["eventId"];
    if(isset($_GET["stateId"])){
        $state=$_GET["stateId"];
        if($state=="Pubblicato"){
            $sql = "SELECT * FROM published_events WHERE Id = '$eventId'";
        }else{
            $sql = "SELECT * FROM editing_events WHERE Id = '$eventId'";
        }
    }else{
        $sql = "SELECT * FROM editing_events WHERE Id = '$eventId'";
    }
    $result = mysqli_query($OggiSTI_conn_adm,$sql);
    $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
    $oldDate = $row["Date"];
    $date = date('d-m-Y', strtotime($oldDate));
    $dateCorr = str_replace('-', '/', $date);
    $itaTitle = $row["ItaTitle"]; 
    $engTitle = $row["EngTitle"];
    $itaAbstract = $row["ItaAbstract"];
    $engAbstract = $row["EngAbstract"];
    $image = $row["Image"];
    $imageCaption = $row["ImageCaption"];
    $itaDescription = $row["ItaDescription"];
    $engDescription = $row["EngDescription"];
    $textReferences = $row["TextReferences"];
    $keywords = $row["Keywords"];
    $editors = $row["Editors"];
    $comment = $row["Comment"];
    $state = $row["State"];
    if($state=="In redazione"){
        $editorsMatch="/".$userId."/i";
        if (preg_match($editorsMatch, $editors)) {
            $editors = $editors;
        }else{
            $editors = $editors.", ".$userId;
        }
    }
}else{
    // Create a new event
    $menuEvento = "Aggiungi evento";
    $creationQuery = "INSERT INTO editing_events (ItaTitle) VALUES ('')";
    mysqli_query($OggiSTI_conn_adm, $creationQuery);    
    $result = mysqli_query($OggiSTI_conn_adm, "SELECT MAX(Id) FROM editing_events");
    $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
    $eventId = $row["MAX(Id)"];
    $editors=$userId;
    $sql2 = "INSERT INTO editing (Event_Id, Editor, Type) VALUES ('$eventId', '$userId', '1')";
    mysqli_query($OggiSTI_conn_adm, $sql2);
}
// Check if there is an image message
if(isset($_GET["imageMessage"])){
    $imageMessage="<br/>".$_GET["imageMessage"];
    }

// Check if there is a message
if(isset($_GET["message"])){
    $mess=$_GET["message"];
    if($mess=="salva"){
        $message="Evento salvato correttamente".$imageMessage;
        $class="alert alert-success";
    }
    if($mess=="modifica"){
        $message="Stai modificando un evento".$imageMessage;
        $class="alert alert-warning";
    }
    if($mess=="modificaVeloce"){
        $message="Stai modificando un evento senza lasciare traccia della redazione".$imageMessage;
        $class="alert alert-danger";
    }
    if($mess=="errore"){
        $message="Evento NON salvato".$imageMessage;
        $class="alert alert-danger";
    }
    
}else{
    $message="Hai creato un nuovo evento".$imageMessage;
    $class="alert alert-warning";
}




// Control if launch the preview
if(isset($_GET["preview"])){
    $prev = $_GET["preview"];
    if($prev=="ok"){
        $link = "<script>window.open(\"../../OggiSTI_preview.php?eventId=$eventId&stateId=Preview\", \"previewOggiSTI\", \"width=864,height=1000\")</script>";
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
<strong>Commento:</strong> <?php echo $comment; ?>
</div>-->
    


<div class="jumbotron">
<!-- Comment -->
<div id="visualizzaCommento" class="panel panel-default">
    <div class="panel-body">Commento: <br/> <?php echo $comment; ?> </div>
</div>
<br class="stop"/>
<!-- Message alert -->
<div class="<?php echo $class; ?>" id="alertEvento">
    <button type="button" class="close" data-dismiss="alert">x</button>
    <p><?php echo $message; ?></p>
</div>
<!-- Language button -->
<div id="btnLanguage">
    <button id="btnItalian" class="btn active">Italiano</button><button id="btnEnglish" class="btn">English</button>
</div>

<!-- Form starts here -->
<!-- This form allows the writing of events -->
<form id="addEvent" method="post" action="../Api/updateEvents.php" enctype="multipart/form-data">

    <!-- ID -->
    <div class="col-xs-2">
    <label for="eventId">Id evento</label>
	<input type="text" name="eventId" class="form-control" id="eventId" readonly value="<?php if($mess=="errore"){echo $_SESSION['eventId'];}else{ echo $eventId;} ?>">
    </div>

	<!-- Date -->
	<div id="formData" class="form-group col-xs-10">
	<label for="eventDate">Data dell'evento</label>
	<input type="datetime" name="eventDate" class="form-control" id="eventDate" placeholder="dd/mm/yyyy" value="<?php if($mess=="errore"){echo $_SESSION['eventDate'];}else{echo $dateCorr;} ?>">
	<span id="glyphiconDate"></span>
	<span id="helpDate" class="help-block">Per maggiori informazioni consultare le <a href="../../LineeGuida/#LGData" target="_blank">linee guida sulla data</a></span>
	</div>

	<br class="stop"/>

	<!-- Title -->
    <h2 id="editTitle">Titolo</h2>
    <!-- Italian -->
	<div id="formItaTitle" class="form-group col-xs-12">
	<label for='itaTitle'>in italiano</label>
	<textarea name="itaTitle" class="form-control" rows="2" id="itaTitle"><?php if($mess=="errore"){echo $_SESSION['itaTitle'];}else{ echo $itaTitle;} ?></textarea>
	<span id="glyphiconTitleIta"></span>
	<span id="countBoxItaTitle" class="help-block pull-right">140</span>
	<span id="helpTitleIta" class="help-block">La dimensione massima consigliata è di 70 caratteri. Per maggiori informazioni consultare le <a href="../../LineeGuida/#LGTitolo" target="_blank">linee guida sul titolo</a></span>
	</div>
    <!-- English -->
	<div id="formEngTitle" class="form-group col-xs-12 hidden">
	<label for='engTitle'>in English</label>
	<textarea name="engTitle" class="form-control" rows="2" id="engTitle"><?php if($mess=="errore"){echo $_SESSION['engTitle'];}else{ echo $engTitle;} ?></textarea>
	<span id="glyphiconTitleEng"></span>
	<span id="countBox_engTitle" class="help-block pull-right">140</span>
	<span id="helpTitleEng" class="help-block">The maximum recommended size is 70 characters. For more information consult <a href="../../LineeGuida/#LGTitolo" target="_blank">title's guidelines</a></span>
	</div>

	<!-- Image -->
    <br/>
    <h2 id="editImg" class="custom-file">Immagine</h2>
    <img id="oggiSTI_imageEvento" src="<?php if($mess=="errore"){echo "../Img/eventi/".$_SESSION['image'];}else{ echo "../Img/eventi/".$image;} ?>" alt="Nessuna image precedente"/>
    <div class="col-xs-8">
    <input type="text" name="oldImage" class="form-control" id="oldImage" readonly value="<?php if($mess=="errore"){echo $_SESSION['image'];}else{ echo $image;} ?>">
	<br/>
    </div>
    <div class="col-xs-4">
	<input type="file" name="image" id="image" class="custom-file-input"/>
    </div>
    <br class="stop" />
    <!-- Reference Image -->
    <div class="col-xs-12">
    <label for='imageCaption'>Fonte dell'image</label>
	<textarea name="imageCaption" class="form-control" rows="1" id="imageCaption"><?php if($mess=="errore"){echo $_SESSION['imageCaption'];}else{ echo $imageCaption;} ?></textarea>
    <span id="helpImg" class="help-block">Per maggiori informazioni consultare le <a href="../../LineeGuida/#LGImm" target="_blank">linee guida sull'image</a></span>
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
	<div id="formItaAbstract" class="form-group col-xs-12">
    <label for='itaAbstract'>in Italiano</label>
	<textarea name="itaAbstract" class="form-control textControl" rows="5" id="itaAbstract"><?php if($mess=="errore"){echo $_SESSION['itaAbstract'];}else{ echo $itaAbstract;} ?></textarea>
	<span id="glyphiconAbstrIta"></span>
	<span id="helpAbstrIta" class="help-block">La dimensione massima consigliata è di 30 parole. Per maggiori informazioni consultare le <a href="../../LineeGuida/#LGDescr" target="_blank">linee guida sulla descrizione</a></span>
    </div>
    <!-- English -->
	<div id="formEngAbstract" class="form-group col-xs-12 hidden">
	<label for='engAbstract'>in English</label>
    <a href="#" data-toggle="tooltip" title="Brief description have to provide information to excite curiosity. The recommended size is about 30 words."><span class="glyphicon glyphicon-info-sign"></span></a>
	<textarea name="engAbstract" class="form-control textControl" rows="5" id="engAbstract"><?php if($mess=="errore"){echo $_SESSION['engAbstract'];}else{ echo $engAbstract;} ?></textarea>
	<span id="glyphiconAbstrEng"></span>
	<span id="helpAbstrEng" class="help-block">The maximum recommended size is 30 words. For more information consult <a href="../../LineeGuida/#LGDescr" target="_blank">description's guidelines</a></span>
    </div>

	<br class="stop" />

	<!-- Deep description --> 
    <h2 id="editDeep">Descrizione di approfondimento</h2>
    <!-- Italian -->
	<div id="formItaDescription" class="form-group col-xs-12">
	<label for='itaDescription'>in italiano</label>
	<textarea name="itaDescription" class="form-control longTextControl" rows="10" id="itaDescription"><?php if($mess=="errore"){echo $_SESSION['itaDescription'];}else{ echo $itaDescription;} ?></textarea>
    <span id="helpDescIta" class="help-block">La dimensione massima consigliata è di 150 parole. Per maggiori informazioni consultare le <a href="../../LineeGuida/#LGDescr" target="_blank">linee guida sulla descrizione</a></span>
	</div>
	<!-- English -->
	<div id="formEngDescription" class="form-group col-xs-12 hidden">
	<label for='engDescription'>in English</label>
	<textarea name="engDescription" class="form-control longTextControl" rows="10" id="engDescription"><?php if($mess=="errore"){echo $_SESSION['engDescription'];}else{ echo $engDescription;} ?></textarea>
    <span id="helpDescEng" class="help-block">The maximum recommended size is 150 words. For more information consult <a href="../../LineeGuida/#LGDescr" target="_blank">description's guidelines</a></span>
	</div>

    <br class="stop" />
	
    <!-- References -->
    <h2 id="editRef">Riferimenti</h2>
    <div id="formRiferimenti" class="form-group col-xs-12">
	<label for='textReferences'>Riferimenti</label>
	<textarea name="textReferences" class="form-control textControl" rows="5" id="textReferences"><?php if($mess=="errore"){echo $_SESSION['textReferences'];}else{ echo $textReferences;} ?></textarea>
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

    <!-- Editors -->
     <div class='col-lg-12 hidden'>
    <label for="editors">Autori</label>
	<input type="text" name="editors" class="form-control" id="editors" readonly value="<?php echo $editors; ?>">
    </div>
	<br/>
         
    <!-- Saved by -->
    <div class='col-lg-2 hidden'>
    <label for="saved">Salvato da:</label>
	<input type="text" name="saved" class="form-control" id="saved" readonly value="<?php echo $userId; ?>">
    </div>

	<!-- State -->
    <?php if($editorPermission == 1 && $reviserPermission == 0 && $state!="Pubblicato"){
        echo "<div class='col-lg-3 hidden'>";
        echo "<label for='IApprovation'>I approvazione</label>
        <input type='text' name='IApprovation' class='form-control' id='IApprovation' readonly value='0'></div>";
        echo "<div class='col-lg-3 hidden'>";
        echo "<label for='IIApprovation'>II approvazione</label>
        <input type='text' name='IIApprovation' class='form-control' id='IIApprovation' readonly value='0'></div>";
        echo "<div class='col-lg-4 hidden'>";
        echo "<label for='state'>Stato</label>
        <input type='text' name='state' class='form-control' id='state' readonly value='Approvazione 0/2'></div>";
        echo "<br class='stop'/>";
    }else if($editorPermission == 1 && $reviserPermission == 1 && $state!="Pubblicato"){
        echo "<div class='col-lg-3 hidden'>";
        echo "<label for='IApprovation'>I approvazione</label>
        <input type='text' name='IApprovation' class='form-control' id='IApprovation' readonly value='".$userId."'></div>";
        echo "<div class='col-lg-3 hidden'>";
        echo "<label for='IIApprovation'>II approvazione</label><br/>
        <input type='text' name='IIApprovation' class='form-control'  id='IIApprovation' readonly value='0'></div>";
        echo "<div class='col-lg-4 hidden'>";
        echo "<label for='state'>Stato</label><br/>
        <input type='text' name='state' class='form-control'  id='state' readonly value='Approvazione 1/2'></div>";
        echo "<br class='stop'/>";
    }else if($state=="Pubblicato"){
        echo "<div class='col-lg-4 hidden'>";
        echo "<label for='state'>Stato</label><br/>
        <input type='text' name='state' class='form-control'  id='state' readonly value='Pubblicato'></div>";
        echo "<br class='stop'/>";
     }
    ?>
    <br/>

    <!-- Buttons -->
    <div class="btn-group pull-right">
    <?php 
    if ($mess=="modificaVeloce"){
        echo '<button type="submit" name="salvaChiudi" id="salvaChiudi" class="btn btn-success">Salva e chiudi</button>';
    } else{
    echo '<button type="submit" name="salva" id="salva" class="btn btn-success">Salva</button>
    <button type="submit" name="preview" id="preview" class="btn btn-warning">Preview</button>
	<button type="button" id="applica" class="btn btn-info" data-toggle="modal" data-target="#modalApprovazione">Invia in approvazione</button>';
    }
    ?>
    </div>

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
